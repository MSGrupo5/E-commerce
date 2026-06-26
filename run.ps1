$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location -LiteralPath $projectRoot

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  Iniciando NexusTech E-commerce Stack" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Abriendo 3 terminales..." -ForegroundColor Yellow
Write-Host ""

Start-Process powershell -ArgumentList "-NoExit", "-Command", "Set-Location '$projectRoot'; php artisan serve"
Start-Sleep -Milliseconds 1500

Start-Process powershell -ArgumentList "-NoExit", "-Command", "Set-Location '$projectRoot'; npm run build"
Start-Sleep -Milliseconds 1500

Start-Process powershell -ArgumentList "-NoExit", "-Command", "Set-Location '$projectRoot'; cloudflared tunnel --url http://localhost:8000"
