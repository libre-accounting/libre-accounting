{{/*
Expand the name of the chart.
*/}}
{{- define "libre-accounting.name" -}}
{{- default .Chart.Name .Values.nameOverride | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Create a default fully qualified app name. Truncated to 47 characters so the
longest resource-name suffix this chart appends ("-test-connection", 16 chars)
still fits Kubernetes' 63-character name limit.
*/}}
{{- define "libre-accounting.fullname" -}}
{{- if .Values.fullnameOverride }}
{{- .Values.fullnameOverride | trunc 47 | trimSuffix "-" }}
{{- else }}
{{- $name := default .Chart.Name .Values.nameOverride }}
{{- if contains $name .Release.Name }}
{{- .Release.Name | trunc 47 | trimSuffix "-" }}
{{- else }}
{{- printf "%s-%s" .Release.Name $name | trunc 47 | trimSuffix "-" }}
{{- end }}
{{- end }}
{{- end }}

{{/*
Chart name and version for the chart label.
*/}}
{{- define "libre-accounting.chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Common labels.
*/}}
{{- define "libre-accounting.labels" -}}
helm.sh/chart: {{ include "libre-accounting.chart" . }}
{{ include "libre-accounting.selectorLabels" . }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end }}

{{/*
Selector labels.
*/}}
{{- define "libre-accounting.selectorLabels" -}}
app.kubernetes.io/name: {{ include "libre-accounting.name" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
{{- end }}

{{/*
Image reference: digest wins over tag; tag defaults to the chart appVersion.
*/}}
{{- define "libre-accounting.image" -}}
{{- if .Values.image.digest }}
{{- printf "%s@%s" .Values.image.repository .Values.image.digest }}
{{- else }}
{{- printf "%s:%s" .Values.image.repository (default .Chart.AppVersion .Values.image.tag) }}
{{- end }}
{{- end }}

{{/*
ServiceAccount name.
*/}}
{{- define "libre-accounting.serviceAccountName" -}}
{{- if .Values.serviceAccount.create }}
{{- default (include "libre-accounting.fullname" .) .Values.serviceAccount.name }}
{{- else }}
{{- default "default" .Values.serviceAccount.name }}
{{- end }}
{{- end }}

{{/*
Hostname derived from app.url. urlParse's "host" keeps a :port suffix, which
Gateway API hostnames (and probe Host headers) must not contain.
*/}}
{{- define "libre-accounting.hostname" -}}
{{- regexReplaceAll ":\\d+$" (urlParse (required "app.url is required (e.g. https://accounting.example.com)" .Values.app.url)).host "" }}
{{- end }}

{{/*
Whether any driver uses redis.
*/}}
{{- define "libre-accounting.usesRedis" -}}
{{- if or (eq .Values.queue.connection "redis") (eq .Values.session.driver "redis") (eq .Values.cache.driver "redis") }}true{{- end }}
{{- end }}

{{/*
SESSION_SECURE_COOKIE: explicit value wins, otherwise derived from the
app.url scheme.
*/}}
{{- define "libre-accounting.secureCookie" -}}
{{- if kindIs "bool" .Values.session.secureCookie }}
{{- ternary "true" "false" .Values.session.secureCookie }}
{{- else if ne (toString .Values.session.secureCookie) "" }}
{{- .Values.session.secureCookie }}
{{- else }}
{{- ternary "true" "false" (hasPrefix "https://" .Values.app.url) }}
{{- end }}
{{- end }}

{{/*
Render-time validation of cross-field constraints. Included from
configmap.yaml so it runs on every install/upgrade/template.
*/}}
{{- define "libre-accounting.validate" -}}
{{- $_ := required "app.url is required (e.g. https://accounting.example.com)" .Values.app.url }}
{{- if not (or (hasPrefix "http://" .Values.app.url) (hasPrefix "https://" .Values.app.url)) }}
{{- fail "app.url must start with http:// or https://" }}
{{- end }}
{{- if not (or .Values.app.key .Values.app.existingSecret) }}
{{- fail "Set app.key (generate with: echo \"base64:$(openssl rand -base64 32)\") or app.existingSecret. The key is never auto-generated: losing it makes encrypted data unreadable." }}
{{- end }}
{{- if not (has .Values.db.connection (list "mysql" "pgsql" "sqlite")) }}
{{- fail "db.connection must be one of: mysql, pgsql, sqlite" }}
{{- end }}
{{- if ne .Values.db.connection "sqlite" }}
{{- $_ := required "db.host is required (the chart does not bundle a database)" .Values.db.host }}
{{- if not (or .Values.db.password .Values.db.existingSecret) }}
{{- fail "Set db.password or db.existingSecret" }}
{{- end }}
{{- end }}
{{- if and (include "libre-accounting.usesRedis" .) (not .Values.redis.host) }}
{{- fail "redis.host is required when queue.connection, session.driver or cache.driver is 'redis'" }}
{{- end }}
{{- if gt (int .Values.replicaCount) 1 }}
{{- if and .Values.persistence.enabled (not (has "ReadWriteMany" .Values.persistence.accessModes)) }}
{{- fail "replicaCount > 1 requires persistence.accessModes to include ReadWriteMany (or persistence.enabled=false with FILESYSTEM_DISK=s3 via extraEnv)" }}
{{- end }}
{{- if eq .Values.session.driver "file" }}
{{- fail "replicaCount > 1 requires session.driver=redis or session.driver=database" }}
{{- end }}
{{- if eq .Values.cache.driver "file" }}
{{- fail "replicaCount > 1 requires cache.driver=redis or cache.driver=database" }}
{{- end }}
{{- end }}
{{- if and .Values.httpRoute.enabled (not .Values.gateway.enabled) (not .Values.httpRoute.parentRefs) }}
{{- fail "httpRoute.parentRefs must reference at least one Gateway (or set gateway.enabled=true, or httpRoute.enabled=false)" }}
{{- end }}
{{- if and (not .Values.persistence.enabled) .Values.worker.enabled (ne .Values.queue.connection "sync") }}
{{- fail "worker.enabled with persistence.enabled=false gives web and worker pods separate emptyDirs, breaking company export/import (storage/app/temp must be shared). Enable persistence, or set queue.connection=sync and worker.enabled=false" }}
{{- end }}
{{- if .Values.setup.enabled }}
{{- if eq .Values.db.connection "sqlite" }}
{{- fail "setup.enabled is not supported with db.connection=sqlite (the volume does not exist at pre-install time). Deploy with setup.enabled=false, then run: kubectl exec deploy/<release>-web -- php artisan install ..." }}
{{- end }}
{{- $_ := required "setup.adminEmail is required when setup.enabled=true" .Values.setup.adminEmail }}
{{- if not (or .Values.setup.adminPassword .Values.setup.existingSecret) }}
{{- fail "Set setup.adminPassword or setup.existingSecret when setup.enabled=true" }}
{{- end }}
{{- end }}
{{- end }}

{{/*
envFrom for runtime app containers (web, worker, scheduler).
*/}}
{{- define "libre-accounting.envFrom" -}}
- configMapRef:
    name: {{ include "libre-accounting.fullname" . }}
{{- with .Values.extraEnvFrom }}
{{ toYaml . }}
{{- end }}
{{- end }}

{{/*
Secret-backed env entries plus extraEnv, for runtime app containers.
*/}}
{{- define "libre-accounting.secretEnv" -}}
- name: APP_KEY
  valueFrom:
    secretKeyRef:
      name: {{ default (include "libre-accounting.fullname" .) .Values.app.existingSecret }}
      key: {{ ternary .Values.app.existingSecretKey "app-key" (ne .Values.app.existingSecret "") }}
{{- if ne .Values.db.connection "sqlite" }}
- name: DB_PASSWORD
  valueFrom:
    secretKeyRef:
      name: {{ default (include "libre-accounting.fullname" .) .Values.db.existingSecret }}
      key: {{ ternary .Values.db.secretKeys.password "db-password" (ne .Values.db.existingSecret "") }}
{{- end }}
{{- if and (include "libre-accounting.usesRedis" .) (or .Values.redis.password .Values.redis.existingSecret) }}
- name: REDIS_PASSWORD
  valueFrom:
    secretKeyRef:
      name: {{ default (include "libre-accounting.fullname" .) .Values.redis.existingSecret }}
      key: {{ ternary .Values.redis.secretKeys.password "redis-password" (ne .Values.redis.existingSecret "") }}
{{- end }}
{{- if or .Values.mail.password .Values.mail.existingSecret }}
- name: MAIL_PASSWORD
  valueFrom:
    secretKeyRef:
      name: {{ default (include "libre-accounting.fullname" .) .Values.mail.existingSecret }}
      key: {{ ternary .Values.mail.secretKeys.password "mail-password" (ne .Values.mail.existingSecret "") }}
{{- end }}
{{- with .Values.extraEnv }}
{{ toYaml . }}
{{- end }}
{{- end }}

{{/*
Inline database env for hook Jobs. Hook pods cannot reference the chart
ConfigMap (it does not exist yet at pre-install time), so the connection
settings are rendered directly. The password secret differs per hook:
call with (dict "root" $ "passwordSecret" <name> "passwordKey" <key>).
*/}}
{{- define "libre-accounting.dbEnv" -}}
{{- $v := .root.Values }}
- name: DB_CONNECTION
  value: {{ $v.db.connection | quote }}
- name: DB_HOST
  value: {{ $v.db.host | quote }}
- name: DB_PORT
  value: {{ $v.db.port | quote }}
- name: DB_DATABASE
  value: {{ $v.db.database | quote }}
- name: DB_USERNAME
  value: {{ $v.db.username | quote }}
- name: DB_PREFIX
  value: {{ $v.db.prefix | quote }}
{{- if ne $v.db.connection "sqlite" }}
- name: DB_PASSWORD
  valueFrom:
    secretKeyRef:
      name: {{ .passwordSecret }}
      key: {{ .passwordKey }}
{{- end }}
{{- end }}

{{/*
initContainer that waits for the database to accept connections. Expects the
DB_* env of the surrounding pod spec (pass the same env as the main
container). Call with (dict "root" $ "env" <rendered env yaml>).
*/}}
{{- define "libre-accounting.waitForDb" -}}
- name: wait-for-db
  image: {{ include "libre-accounting.image" .root }}
  imagePullPolicy: {{ .root.Values.image.pullPolicy }}
  command:
    - /bin/sh
    - -c
    - |
      if [ "$DB_CONNECTION" = "sqlite" ]; then exit 0; fi
      waited=0
      until php -r '
        $dsn = sprintf("%s:host=%s;port=%s;dbname=%s",
          getenv("DB_CONNECTION"), getenv("DB_HOST"), getenv("DB_PORT"), getenv("DB_DATABASE"));
        try { new PDO($dsn, getenv("DB_USERNAME"), getenv("DB_PASSWORD"), [PDO::ATTR_TIMEOUT => 5]); }
        catch (Exception $e) { fwrite(STDERR, $e->getMessage() . PHP_EOL); exit(1); }
      '; do
        waited=$((waited + 5))
        if [ "$waited" -ge "{{ .root.Values.setup.dbWaitTimeoutSeconds }}" ]; then
          echo "Database not reachable after {{ .root.Values.setup.dbWaitTimeoutSeconds }}s" >&2
          exit 1
        fi
        echo "Waiting for database..."
        sleep 5
      done
  securityContext:
    allowPrivilegeEscalation: false
    runAsNonRoot: true
    runAsUser: 33
    runAsGroup: 33
    seccompProfile:
      type: RuntimeDefault
    capabilities:
      drop: [ALL]
  env:
{{ .env | indent 4 }}
{{- end }}

{{/*
Shared volumes for app pods.
*/}}
{{- define "libre-accounting.volumes" -}}
{{- if .Values.persistence.enabled }}
- name: data
  persistentVolumeClaim:
    claimName: {{ default (include "libre-accounting.fullname" .) .Values.persistence.existingClaim }}
{{- else }}
- name: data
  emptyDir: {}
{{- end }}
- name: php-ini
  configMap:
    name: {{ include "libre-accounting.fullname" . }}-php
{{- end }}

{{/*
Shared volume mounts for app containers.
*/}}
{{- define "libre-accounting.volumeMounts" -}}
- name: data
  mountPath: {{ .Values.persistence.mountPath }}
- name: php-ini
  mountPath: /usr/local/etc/php/conf.d/zz-libre-accounting.ini
  subPath: zz-libre-accounting.ini
{{- end }}

{{/*
Non-root security context for worker/scheduler/job containers (these bypass
the image entrypoint and run artisan directly as www-data).
*/}}
{{- define "libre-accounting.nonRootSecurityContext" -}}
allowPrivilegeEscalation: false
runAsNonRoot: true
runAsUser: 33
runAsGroup: 33
seccompProfile:
  type: RuntimeDefault
capabilities:
  drop: [ALL]
{{- end }}
