# Deploy Script for SSIS v2.1
# This script rebuilds containers and restarts the tunnel

Write-Host "🔄 Stopping containers..." -ForegroundColor Cyan
docker compose down

Write-Host "🏗️  Building and starting containers..." -ForegroundColor Cyan
docker compose up --build -d

Write-Host "⏳ Waiting for containers to be ready..." -ForegroundColor Cyan
Start-Sleep -Seconds 5

Write-Host "🔄 Restarting Cloudflare tunnel..." -ForegroundColor Cyan
docker restart ssis_tunnel

Write-Host "✅ Deployment complete!" -ForegroundColor Green
Write-Host ""
Write-Host "📝 Next steps:" -ForegroundColor Yellow
Write-Host "   1. Check localhost:3000 to verify changes"
Write-Host "   2. For south2es.site, either:"
Write-Host "      - Enable Development Mode in Cloudflare (recommended)"
Write-Host "      - Purge cache in Cloudflare dashboard"
Write-Host "      - Hard refresh browser (Ctrl + Shift + R)"
Write-Host ""
Write-Host "🌐 Local: http://localhost:3000" -ForegroundColor Blue
Write-Host "🌐 Domain: https://south2es.site" -ForegroundColor Blue
