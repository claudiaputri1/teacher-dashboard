# Debug API Test
$BASE_URL = "http://localhost:8000/api"
$EMAIL = "test@example.com"
$PASSWORD = "password123"

Write-Host "=== DEBUG API TEST ===" -ForegroundColor Green

# Step 1: Test token generation
Write-Host "1. Testing token generation..." -ForegroundColor Yellow
$tokenBody = @{
    email = $EMAIL
    password = $PASSWORD
} | ConvertTo-Json

try {
    $tokenResponse = Invoke-RestMethod -Uri "$BASE_URL/auth/token" -Method Post -Body $tokenBody -ContentType "application/json"
    Write-Host "✅ Token Response:" -ForegroundColor Green
    Write-Host ($tokenResponse | ConvertTo-Json -Depth 2) -ForegroundColor Cyan
    
    $TOKEN = $tokenResponse.token
    Write-Host "Token: $($TOKEN.Substring(0, 30))..." -ForegroundColor White
    
    # Step 2: Test dashboard stats with detailed response
    Write-Host "`n2. Testing dashboard stats..." -ForegroundColor Yellow
    $headers = @{
        "Authorization" = "Bearer $TOKEN"
        "Content-Type" = "application/json"
        "Accept" = "application/json"
    }
    
    $statsResponse = Invoke-WebRequest -Uri "$BASE_URL/dashboard/stats" -Method Get -Headers $headers
    Write-Host "✅ Stats Response Status: $($statsResponse.StatusCode)" -ForegroundColor Green
    Write-Host "✅ Stats Response Headers:" -ForegroundColor Green
    $statsResponse.Headers | Format-Table
    Write-Host "✅ Stats Response Content:" -ForegroundColor Green
    Write-Host $statsResponse.Content -ForegroundColor Cyan
    
} catch {
    Write-Host "❌ Error: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.Exception.Response) {
        Write-Host "Response Status: $($_.Exception.Response.StatusCode)" -ForegroundColor Red
        Write-Host "Response Content: $($_.Exception.Response.Content)" -ForegroundColor Red
    }
}

Write-Host "`n=== DEBUG COMPLETE ===" -ForegroundColor Green
