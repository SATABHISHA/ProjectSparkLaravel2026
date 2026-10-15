param(
    [switch]$SkipMigrations
)

$ErrorActionPreference = 'Stop'

$ProjectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$HostingerHost = '82.25.106.143'
$HostingerPort = 65002
$HostingerUser = 'u473577775'
$RemoteRoot = '/home/u473577775/domains/bbswohealth.in/public_html'
$Stamp = Get-Date -Format 'yyyyMMdd-HHmmss'
$ArchiveName = "projectspark-$Stamp.tar.gz"
$ArchivePath = Join-Path $env:TEMP $ArchiveName
$RemoteArchive = "/tmp/$ArchiveName"
$RemoteScriptPath = Join-Path $env:TEMP "projectspark-deploy-$Stamp.sh"

if (-not (Get-Command ssh.exe -ErrorAction SilentlyContinue)) {
    throw 'OpenSSH ssh.exe is required but was not found on PATH.'
}

if (-not (Get-Command scp.exe -ErrorAction SilentlyContinue)) {
    throw 'OpenSSH scp.exe is required but was not found on PATH.'
}

if (-not (Get-Command tar.exe -ErrorAction SilentlyContinue)) {
    throw 'tar.exe is required but was not found on PATH.'
}

try {
    Write-Host "Creating deployment archive $ArchiveName ..."
    & tar.exe -czf $ArchivePath `
        '--exclude=.git' `
        '--exclude=.env' `
        '--exclude=vendor' `
        '--exclude=node_modules' `
        '--exclude=storage/logs' `
        '--exclude=.phpunit.result.cache' `
        '-C' $ProjectRoot '.'

    if ($LASTEXITCODE -ne 0) {
        throw 'Could not create the deployment archive.'
    }

    Write-Host 'Uploading deployment archive. SSH may ask for the Hostinger password.'
    & scp.exe -P $HostingerPort $ArchivePath "${HostingerUser}@${HostingerHost}:$RemoteArchive"

    if ($LASTEXITCODE -ne 0) {
        throw 'Could not upload the deployment archive.'
    }

    $migrationCommand = if ($SkipMigrations) { 'true' } else { 'php "$REMOTE_ROOT/artisan" migrate --force' }
    $remoteScript = @'
set -euo pipefail

REMOTE_ROOT="__REMOTE_ROOT__"
REMOTE_ARCHIVE="__REMOTE_ARCHIVE__"
RELEASE_DIR="$REMOTE_ROOT/.deploy/releases/__STAMP__"

mkdir -p "$RELEASE_DIR"
tar -xzf "$REMOTE_ARCHIVE" -C "$RELEASE_DIR"

if [ ! -f "$REMOTE_ROOT/.env" ]; then
    echo "Missing $REMOTE_ROOT/.env. Configure production secrets on the server before deploying."
    exit 1
fi

shopt -s dotglob nullglob
for item in "$RELEASE_DIR"/* "$RELEASE_DIR"/.[!.]*; do
    [ -e "$item" ] || continue
    base="$(basename "$item")"
    case "$base" in
        .env|storage|vendor|public|deploy)
            continue
            ;;
    esac
    rm -rf "$REMOTE_ROOT/$base"
    cp -a "$item" "$REMOTE_ROOT/$base"
done

rm -f "$REMOTE_ROOT/default.php"
cp -a "$RELEASE_DIR/public/." "$REMOTE_ROOT/"
cp "$RELEASE_DIR/deploy/hostinger-index.php" "$REMOTE_ROOT/index.php"
cp "$RELEASE_DIR/deploy/hostinger-htaccess" "$REMOTE_ROOT/.htaccess"

mkdir -p \
    "$REMOTE_ROOT/storage/framework/cache" \
    "$REMOTE_ROOT/storage/framework/sessions" \
    "$REMOTE_ROOT/storage/framework/views" \
    "$REMOTE_ROOT/storage/logs" \
    "$REMOTE_ROOT/bootstrap/cache"

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --working-dir="$REMOTE_ROOT"
__MIGRATION_COMMAND__
php "$REMOTE_ROOT/artisan" storage:link || true
php "$REMOTE_ROOT/artisan" optimize:clear
chmod -R ug+rwX "$REMOTE_ROOT/storage" "$REMOTE_ROOT/bootstrap/cache"

rm -f "$REMOTE_ARCHIVE"
rm -rf "$RELEASE_DIR"
echo "Project SPARK deployment __STAMP__ completed."
'@

    $remoteScript = $remoteScript.Replace('__REMOTE_ROOT__', $RemoteRoot)
    $remoteScript = $remoteScript.Replace('__REMOTE_ARCHIVE__', $RemoteArchive)
    $remoteScript = $remoteScript.Replace('__STAMP__', $Stamp)
    $remoteScript = $remoteScript.Replace('__MIGRATION_COMMAND__', $migrationCommand)

    [System.IO.File]::WriteAllText(
        $RemoteScriptPath,
        $remoteScript,
        [System.Text.UTF8Encoding]::new($false)
    )

    Write-Host 'Running the remote deployment. SSH may ask for the Hostinger password.'
    Get-Content -Raw -LiteralPath $RemoteScriptPath | & ssh.exe -p $HostingerPort "${HostingerUser}@${HostingerHost}" 'bash -s'

    if ($LASTEXITCODE -ne 0) {
        throw 'The remote deployment failed.'
    }

    Write-Host 'Deployment completed. Existing database rows are preserved; only pending migrations were applied.'
}
finally {
    Remove-Item -LiteralPath $ArchivePath -Force -ErrorAction SilentlyContinue
    Remove-Item -LiteralPath $RemoteScriptPath -Force -ErrorAction SilentlyContinue
}
