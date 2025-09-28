# Simple API Test Script
$BASE_URL = "http://localhost:8000/api"
$EMAIL = "ahmad@test.com"
$PASSWORD = "password"

Write-Host "Testing API..." -ForegroundColor Green

# Generate Token
$tokenBody = @{
    email = $EMAIL
    password = $PASSWORD
} | ConvertTo-Json

try {
    Write-Host "Getting token..." -ForegroundColor Yellow
    $tokenResponse = Invoke-RestMethod -Uri "$BASE_URL/auth/token" -Method Post -Body $tokenBody -ContentType "application/json"
    $TOKEN = $tokenResponse.token
    Write-Host "Token received: $($TOKEN.Substring(0, 20))..." -ForegroundColor Green
    
    # Test Dashboard Stats
    Write-Host "Testing dashboard stats..." -ForegroundColor Yellow
    $headers = @{
        "Authorization" = "Bearer $TOKEN"
        "Content-Type" = "application/json"
    }
    
    $response = Invoke-RestMethod -Uri "$BASE_URL/dashboard/stats" -Method Get -Headers $headers
    Write-Host "Dashboard stats response:" -ForegroundColor Green
    $response | ConvertTo-Json
    
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "Test complete!" -ForegroundColor Green
