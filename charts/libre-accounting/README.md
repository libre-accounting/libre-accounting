# libre-accounting

Deploys [Libre Accounting](https://github.com/libre-accounting/libre-accounting) on Kubernetes:
a web Deployment (apache image flavor), a queue worker Deployment, a scheduler
Deployment, a PVC for uploads, and a Gateway API `HTTPRoute` (no Ingress).

## Prerequisites

- Kubernetes ≥ 1.26, Helm ≥ 3.8
- [Gateway API CRDs](https://gateway-api.sigs.k8s.io/) (standard channel) and a
  Gateway controller (Envoy Gateway, NGINX Gateway Fabric, Cilium, GKE, ...) —
  the chart creates an `HTTPRoute` (and optionally a `Gateway`) but not the CRDs
- An external **MySQL/MariaDB or PostgreSQL** database (the chart does not
  bundle one; `sqlite` is supported for single-replica evaluation only)
- Optional: external Redis for cache/session/queue

## Quick start

1. Create a secret with the encryption key and database password:

   ```sh
   kubectl create secret generic accounting-secrets \
     --from-literal=app-key="base64:$(openssl rand -base64 32)" \
     --from-literal=db-password='...'
   ```

   > **Back up the `app-key` value.** Losing it makes all encrypted data
   > unreadable. The chart never generates a key for you.

2. Write `my-values.yaml`:

   ```yaml
   app:
     url: https://accounting.example.com
     existingSecret: accounting-secrets
   db:
     host: mysql.database.svc
     database: libre_accounting
     username: libre_accounting
     existingSecret: accounting-secrets
     secretKeys:
       password: db-password
   httpRoute:
     parentRefs:
       - name: shared-gateway
         namespace: infra
         sectionName: https
   setup:
     adminEmail: admin@example.com
     adminPassword: change-me     # or setup.existingSecret
   ```

3. First install (and only the first — see below):

   ```sh
   helm install libre-accounting \
     oci://ghcr.io/libre-accounting/charts/libre-accounting \
     -f my-values.yaml --set setup.enabled=true
   ```

4. Upgrades (database migrations run automatically as a pre-upgrade job):

   ```sh
   helm upgrade libre-accounting \
     oci://ghcr.io/libre-accounting/charts/libre-accounting \
     -f my-values.yaml
   ```

Installing from a source checkout instead: `helm install libre-accounting ./charts/libre-accounting -f my-values.yaml`.

## The setup job (first install)

`php artisan install` seeds the schema, the first company and the admin user.
It is **not idempotent**, so the chart runs it as an opt-in `pre-install` hook:

- `setup.enabled=true` **only on the very first `helm install`**. It has no
  effect on `helm upgrade` (Helm never re-runs pre-install hooks), but keep it
  `false` afterwards anyway — especially under ArgoCD, which maps pre-install
  hooks to `PreSync` and re-runs them **on every sync**.
- The web install wizard is not supported on Kubernetes (it writes `.env`
  inside one pod, which is lost on restart). The chart always runs with
  `APP_INSTALLED=true`.
- If the setup job fails, fix the cause and re-run `helm install` (the failed
  job is kept for `kubectl logs`).
- Admin/company credentials live in a hook-scoped Secret that is deleted after
  a successful install; they never reach the runtime pods.

## Secrets

Every credential supports two modes:

| Value | Inline (dev) | Existing secret (recommended) | Default key |
|---|---|---|---|
| Laravel `APP_KEY` | `app.key` | `app.existingSecret` + `app.existingSecretKey` | `app-key` |
| DB password | `db.password` | `db.existingSecret` + `db.secretKeys.password` | `db-password` |
| Redis password | `redis.password` | `redis.existingSecret` + `redis.secretKeys.password` | `redis-password` |
| Mail password | `mail.password` | `mail.existingSecret` + `mail.secretKeys.password` | `mail-password` |
| Admin password (setup) | `setup.adminPassword` | `setup.existingSecret` + `setup.secretKeys.adminPassword` | `admin-password` |

Inline values are stored in a chart-managed Secret annotated
`helm.sh/resource-policy: keep` (it survives uninstall, protecting the
APP_KEY). Pods roll automatically when chart-managed config/secrets change;
rotations inside an `existingSecret` are invisible to the chart — restart with
`kubectl rollout restart deploy -l app.kubernetes.io/instance=<release>`.

Prefer `existingSecret` in production: it keeps credentials out of Helm
values (which are stored in the release Secret), and the `checksum/secret`
pod annotation — a hash of the chart-managed Secret used to roll pods on
change — could otherwise be used by anyone with pod read access to
offline-verify guesses of a *weak* inline password.

## Persistence

One PVC is mounted at `storage/app` in all app pods: user uploads
(`storage/app/uploads`) and export/import staging (`storage/app/temp`, shared
between web and worker). With the default `ReadWriteOnce` access mode, worker
and scheduler pods are pinned to the web pod's node via podAffinity, and all
three Deployments use the `Recreate` strategy so old pods release the volume
before replacements start. On multi-node clusters an upgrade may still spend
a short time in `ContainerCreating` while the volume detaches from the old
node; use `ReadWriteMany` storage to avoid that entirely.

`replicaCount > 1` is refused at render time unless:

- `persistence.accessModes` includes `ReadWriteMany` (or persistence is
  disabled in favor of S3: `extraEnv` with `FILESYSTEM_DISK=s3` + `AWS_*`), and
- `session.driver` and `cache.driver` are `redis` or `database`.

## Gateway API notes

- TLS terminates at the Gateway; the chart sets `SESSION_SECURE_COOKIE=true`
  automatically when `app.url` is `https://`.
- Attaching to a Gateway in another namespace requires that Gateway's
  `allowedRoutes.namespaces` to permit it (the Gateway owner's setting; no
  `ReferenceGrant` is needed for the route's own backend).
- Request body size limits are controller-specific. PHP-side limits are
  chart-managed (`php.uploadMaxFilesize`, default 64M), but the data plane may
  cut requests first:

  | Controller | Default body limit |
  |---|---|
  | NGINX Gateway Fabric | 1m nginx default — raise via `NginxProxy`/snippets |
  | Envoy Gateway / Istio / GKE | unlimited by default |

## sqlite evaluation mode

`db.connection=sqlite` stores the database on the persistence volume. The
setup and migration jobs don't run for sqlite (the volume doesn't exist at
pre-install time); initialize manually after the first deploy:

```sh
kubectl exec deploy/<release>-web -- php artisan install \
  --db-connection=sqlite --db-name=/var/www/html/storage/app/database.sqlite \
  --company-name=... --company-email=... --admin-email=... --admin-password=... \
  --no-interaction
```

## Uninstall

`helm uninstall` keeps the PVC and the chart-managed Secret (both are
annotated `helm.sh/resource-policy: keep`) so data and the APP_KEY survive.
Delete them explicitly if you really want everything gone.

## Values

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| app.debug | bool | `false` | Laravel debug mode (`APP_DEBUG`). |
| app.existingSecret | string | `""` | Name of an existing Secret holding the APP_KEY (recommended for production/GitOps). Takes precedence over `app.key`. |
| app.existingSecretKey | string | `"app-key"` | Key inside `app.existingSecret` that holds the APP_KEY. |
| app.key | string | `""` | Laravel encryption key (`APP_KEY`). Required unless `app.existingSecret` is set. Generate with `echo "base64:$(openssl rand -base64 32)"`. WARNING: losing or changing this key makes all encrypted data unreadable. |
| app.locale | string | `"en-US"` | Default language, also used by the installer. |
| app.logLevel | string | `"info"` | Log verbosity (`LOG_LEVEL`); logs go to stderr. |
| app.timezone | string | `"UTC"` | Container timezone (`TZ`). |
| app.url | string | `""` | Public URL of the installation (`APP_URL`). Required. Its hostname is also the default HTTPRoute hostname, and an `https://` scheme makes session cookies secure-by-default. |
| cache.driver | string | `"file"` | Cache driver: `file`, `database` or `redis`. `file` is fine for a single replica only. |
| db.connection | string | `"mysql"` | Database driver: `mysql`, `pgsql` or `sqlite`. sqlite stores the database file on the persistence volume and is only suitable for single-replica evaluation. |
| db.database | string | `"libre_accounting"` | Database name; for sqlite, an absolute file path on the persistence volume (e.g. `/var/www/html/storage/app/database.sqlite`). |
| db.existingSecret | string | `""` | Name of an existing Secret holding the database password. |
| db.host | string | `""` | Database host. Required for mysql/pgsql — the chart does not bundle a database. |
| db.password | string | `""` | Database password. Ignored when `db.existingSecret` is set. |
| db.port | int | `3306` | Database port. |
| db.prefix | string | `"la_"` | Table name prefix. |
| db.secretKeys.password | string | `"db-password"` | Key inside `db.existingSecret` that holds the password. |
| db.username | string | `"libre_accounting"` | Database user. |
| extraEnv | list | `[]` | Extra environment for the web, worker and scheduler containers (the install/migrate hook Jobs use only the database settings above). Standard EnvVar list, e.g. `[{name: FILESYSTEM_DISK, value: s3}]` to switch uploads to S3. |
| extraEnvFrom | list | `[]` | Extra EnvFromSource list for the web, worker and scheduler containers. |
| fullnameOverride | string | `""` | Fully override the resource name prefix. |
| gateway.annotations | object | `{}` | Extra annotations for the Gateway. |
| gateway.className | string | `""` | GatewayClass name. Required when `gateway.enabled` is true. |
| gateway.enabled | bool | `false` | Create an in-chart Gateway. Most clusters attach to a shared Gateway via `httpRoute.parentRefs` instead. |
| gateway.listeners | list | `[{"name":"http","port":80,"protocol":"HTTP"}]` | Raw Gateway API listener list, e.g. an HTTPS listener with `tls.certificateRefs`. |
| httpRoute.annotations | object | `{}` | Extra annotations for the HTTPRoute. |
| httpRoute.enabled | bool | `true` | Create an HTTPRoute for the web Service. |
| httpRoute.extraRules | list | `[]` | Extra HTTPRoute rules appended verbatim after the default rule. |
| httpRoute.hostnames | list | the hostname of `app.url` | Hostnames for the route. |
| httpRoute.parentRefs | list | `[]` | Gateway(s) to attach to, e.g. `[{name: shared-gateway, namespace: infra, sectionName: https}]`. When `gateway.enabled` is true the chart's own Gateway is added automatically. |
| httpRoute.timeouts | object | `{}` | Optional HTTPRoute rule timeouts (Gateway API v1.1+), e.g. `{request: 60s}`. |
| image.digest | string | `""` | If set, takes precedence over tag (`image@digest`). |
| image.pullPolicy | string | `"IfNotPresent"` | Image pull policy. |
| image.repository | string | `"ghcr.io/libre-accounting/libre-accounting"` | Image repository. Use the plain (apache) image flavor — the chart runs the queue worker and scheduler as separate workloads, so the fpm/supervisor flavors are neither needed nor supported here. |
| image.tag | string | chart `appVersion` | Image tag. |
| imagePullSecrets | list | `[]` | Pull secrets for the app image, applied to every pod. |
| mail.encryption | string | `"tls"` | SMTP transport encryption (`tls` or empty). |
| mail.existingSecret | string | `""` | Name of an existing Secret holding the SMTP password. |
| mail.fromAddress | string | `""` | From address for outgoing mail. |
| mail.fromName | string | `""` | From name for outgoing mail. |
| mail.host | string | `""` | SMTP host. |
| mail.mailer | string | `"smtp"` | Laravel mailer: `smtp` for real deployments (the image default, `mail`, does not work in containers). |
| mail.password | string | `""` | SMTP password. Ignored when `mail.existingSecret` is set. |
| mail.port | int | `587` | SMTP port. |
| mail.secretKeys.password | string | `"mail-password"` | Key inside `mail.existingSecret` that holds the password. |
| mail.username | string | `""` | SMTP username. |
| migrations.enabled | bool | `true` | Run database migrations (`php artisan migrate` with `--force`) as a pre-upgrade hook Job. |
| nameOverride | string | `""` | Partially override the resource name prefix. |
| persistence.accessModes | list | `["ReadWriteOnce"]` | PVC access modes. `replicaCount > 1` requires `ReadWriteMany`. |
| persistence.annotations | object | `{}` | Extra annotations for the created PVC. |
| persistence.enabled | bool | `true` | Persist `storage/app` (user uploads and export/import staging, shared by web, worker and scheduler pods). Disabling it is only valid with `queue.connection=sync` and the worker disabled. |
| persistence.existingClaim | string | `""` | Use an existing PVC instead of creating one. |
| persistence.mountPath | string | `"/var/www/html/storage/app"` | Mount path of the data volume. |
| persistence.size | string | `"10Gi"` | PVC size. |
| persistence.storageClass | string | cluster default | StorageClass for the created PVC. |
| php.extraIni | string | `""` | Extra php.ini lines appended verbatim. |
| php.memoryLimit | string | `"512M"` | `memory_limit`. |
| php.postMaxSize | string | `"64M"` | `post_max_size`. |
| php.uploadMaxFilesize | string | `"64M"` | `upload_max_filesize` for all app containers, rendered into a php.ini drop-in (the stock image ships 2M, too small for real use). |
| queue.connection | string | `"database"` | Queue driver: `database`, `redis` or `sync`. `database` runs jobs (company exports/imports, notifications) in the worker Deployment; `sync` runs them inline in web requests and disables the worker. |
| redis.existingSecret | string | `""` | Name of an existing Secret holding the Redis password. |
| redis.host | string | `""` | External Redis host, used only when queue/session/cache select the `redis` driver. |
| redis.password | string | `""` | Redis password. Ignored when `redis.existingSecret` is set. |
| redis.port | int | `6379` | Redis port. |
| redis.secretKeys.password | string | `"redis-password"` | Key inside `redis.existingSecret` that holds the password. |
| replicaCount | int | `1` | Web replicas. `> 1` requires ReadWriteMany persistence and non-file session/cache drivers (enforced at render time). |
| scheduler.affinity | object | co-scheduled with the web pod when the volume is RWO | Affinity for scheduler pods. |
| scheduler.enabled | bool | `true` | Run the scheduler Deployment (`php artisan schedule:work` — reminders, recurring invoices, temp cleanup). Always a single replica. |
| scheduler.nodeSelector | object | `{}` | Node selector for scheduler pods. |
| scheduler.podAnnotations | object | `{}` | Extra annotations for scheduler pods. |
| scheduler.podLabels | object | `{}` | Extra labels for scheduler pods. |
| scheduler.priorityClassName | string | `""` | PriorityClass for scheduler pods. |
| scheduler.resources | object | `{"limits":{"memory":"512Mi"},"requests":{"cpu":"50m","memory":"128Mi"}}` | Resources for the scheduler container. |
| scheduler.tolerations | list | `[]` | Tolerations for scheduler pods. |
| service.annotations | object | `{}` | Extra annotations for the Service. |
| service.port | int | `80` | Service port. |
| service.type | string | `"ClusterIP"` | Service type. |
| serviceAccount.annotations | object | `{}` | Extra annotations for the ServiceAccount. |
| serviceAccount.create | bool | `true` | Create a dedicated ServiceAccount (tokens are never mounted). |
| serviceAccount.name | string | the release fullname | Use an existing ServiceAccount name instead. |
| session.driver | string | `"file"` | Session driver: `file`, `database` or `redis`. `file` is fine for a single replica only. |
| session.lifetime | int | `30` | Session lifetime in minutes. |
| session.secureCookie | string | derived from the `app.url` scheme (https ⇒ true) | Send session cookies with the Secure flag (true/false). |
| setup.adminEmail | string | `""` | Login email of the admin user. Required when `setup.enabled` is true. |
| setup.adminPassword | string | `""` | Password of the admin user. Ignored when `setup.existingSecret` is set. |
| setup.companyEmail | string | `setup.adminEmail` | Email of the first company. |
| setup.companyName | string | `"My Company"` | Name of the first company. |
| setup.dbWaitTimeoutSeconds | int | `120` | How long the install/migrate jobs wait for the database. |
| setup.enabled | bool | `false` | One-time first install: creates the schema, the first company and the admin user via a pre-install hook Job. Enable ONLY on the very first `helm install` — the installer is not idempotent. Keep disabled under ArgoCD after the first sync (PreSync would re-run it). |
| setup.existingSecret | string | `""` | Name of an existing Secret holding the admin password. |
| setup.secretKeys.adminPassword | string | `"admin-password"` | Key inside `setup.existingSecret` that holds the admin password. |
| web.affinity | object | `{}` | Affinity for web pods. |
| web.containerSecurityContext | object | `{"allowPrivilegeEscalation":false,"capabilities":{"add":["CHOWN","DAC_OVERRIDE","FOWNER","SETGID","SETUID","NET_BIND_SERVICE","KILL"],"drop":["ALL"]},"seccompProfile":{"type":"RuntimeDefault"}}` | Container-level security context for the web container. |
| web.livenessProbe | object | `{"enabled":true,"failureThreshold":3,"periodSeconds":30,"timeoutSeconds":5}` | Liveness probe on `GET /health`. |
| web.nodeSelector | object | `{}` | Node selector for web pods. |
| web.podAnnotations | object | `{}` | Extra annotations for web pods. |
| web.podLabels | object | `{}` | Extra labels for web pods. |
| web.podSecurityContext | object | `{"fsGroup":33,"fsGroupChangePolicy":"OnRootMismatch"}` | Pod-level security context. The web entrypoint must start as root (it fixes ownership and starts apache, whose workers then drop to www-data). |
| web.priorityClassName | string | `""` | PriorityClass for web pods. |
| web.readinessProbe | object | `{"enabled":true,"failureThreshold":3,"periodSeconds":10,"timeoutSeconds":5}` | Readiness probe on `GET /health`. |
| web.resources | object | `{"limits":{"memory":"1Gi"},"requests":{"cpu":"100m","memory":"256Mi"}}` | Resources for the web container. |
| web.startupProbe | object | `{"enabled":true,"failureThreshold":60,"periodSeconds":5}` | Startup probe on `GET /health` (with the `app.url` Host header). |
| web.tolerations | list | `[]` | Tolerations for web pods. |
| worker.affinity | object | co-scheduled with the web pod when the volume is RWO | Affinity for worker pods. |
| worker.enabled | bool | `true` | Run the queue worker Deployment (`php artisan queue:work`). Rendered only when `queue.connection` is not `sync`. |
| worker.maxTime | int | `3600` | Worker exits and restarts after this many seconds to avoid memory creep. |
| worker.nodeSelector | object | `{}` | Node selector for worker pods. |
| worker.podAnnotations | object | `{}` | Extra annotations for worker pods. |
| worker.podLabels | object | `{}` | Extra labels for worker pods. |
| worker.priorityClassName | string | `""` | PriorityClass for worker pods. |
| worker.queues | string | `"exports,jobs,notifications"` | Queues to process, in priority order. |
| worker.replicaCount | int | `1` | Worker replicas. |
| worker.resources | object | `{"limits":{"memory":"768Mi"},"requests":{"cpu":"50m","memory":"192Mi"}}` | Resources for the worker container. |
| worker.sleep | int | `3` | Seconds to sleep when no job is available. |
| worker.terminationGracePeriodSeconds | int | `90` | Grace period so in-flight jobs can finish on shutdown. |
| worker.tolerations | list | `[]` | Tolerations for worker pods. |
| worker.tries | int | `1` | Attempts per job before it is marked failed. |

## Chart docs

This README is generated by [helm-docs](https://github.com/norwoodj/helm-docs)
from `README.md.gotmpl` and the `# --` comments in `values.yaml` — edit those,
then regenerate (CI fails if it is out of date):

```sh
docker run --rm -v "$PWD:/helm-docs" -u "$(id -u)" \
  jnorwood/helm-docs:v1.14.2 --chart-search-root=charts
```

----------------------------------------------
Autogenerated from chart metadata using [helm-docs v1.14.2](https://github.com/norwoodj/helm-docs/releases/v1.14.2)
