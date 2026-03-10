#!/usr/bin/env node

/**
 * Rewrites config/version.php's major/minor/patch/date/time fields to match
 * the version release-it is cutting. Run via release-it's `hooks.before:release`
 * (see .release-it.json), which passes the new version as the first CLI arg.
 *
 * We only ever replace the value of a named key on its own line, so unrelated
 * formatting (spacing, key order, comments) in version.php is left untouched.
 */

const fs = require('fs');
const path = require('path');

const versionFile = path.join(__dirname, '..', 'config', 'version.php');

const newVersion = process.argv[2];

if (!newVersion) {
  console.error('Usage: bump-version.js <major.minor.patch>');
  process.exit(1);
}

const match = newVersion.match(/^(\d+)\.(\d+)\.(\d+)$/);

if (!match) {
  console.error(`Expected a plain "major.minor.patch" version, got: ${newVersion}`);
  process.exit(1);
}

const [, major, minor, patch] = match;

const now = new Date();

const months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December',
];

const date = `${String(now.getUTCDate()).padStart(2, '0')}-${months[now.getUTCMonth()]}-${now.getUTCFullYear()}`;
const time = `${String(now.getUTCHours()).padStart(2, '0')}:${String(now.getUTCMinutes()).padStart(2, '0')}`;

let contents = fs.readFileSync(versionFile, 'utf8');

function setField(source, key, value) {
  const pattern = new RegExp(`('${key}'\\s*=>\\s*)'[^']*'`);

  if (!pattern.test(source)) {
    throw new Error(`Could not find '${key}' in ${versionFile}`);
  }

  return source.replace(pattern, `$1'${value}'`);
}

contents = setField(contents, 'major', major);
contents = setField(contents, 'minor', minor);
contents = setField(contents, 'patch', patch);
contents = setField(contents, 'date', date);
contents = setField(contents, 'time', time);
contents = setField(contents, 'zone', 'GMT +0');

fs.writeFileSync(versionFile, contents);

console.log(`Updated config/version.php to ${major}.${minor}.${patch} (${date} ${time} GMT +0)`);
