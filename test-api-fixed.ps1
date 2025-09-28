# API Testing Script untuk Teacher Dashboard (PowerShell)
# Pastikan server Laravel berjalan di http://localhost:8000

$BASE_URL = "http://localhost:8000/api"
$EMAIL = "ahmadsyaf@gmail.com"  # Ganti dengan email Anda
$PASSWORD = "password"          # Ganti dengan password Anda

Write-Host "🚀 Starting API Tests for Teacher Dashboard" -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Green

# Step 1: Generate API Token
Write-Host "📝 Step 1: Generating API Token..." -ForegroundColor Yellow

$tokenBody = @{
    email = $EMAIL
    password = $PASSWORD
} | ConvertTo-Json

try {
    $tokenResponse = Invoke-RestMethod -Uri "$BASE_URL/auth/token" -Method Post -Body $tokenBody -ContentType "application/json"
    $TOKEN = $tokenResponse.token
    Write-Host "✅ Token generated successfully!" -ForegroundColor Green
    Write-Host "Token: $($TOKEN.Substring(0, 20))..." -ForegroundColor Cyan
} catch {
    Write-Host "❌ Failed to get token. Error: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Please check your credentials and make sure the server is running." -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "🧪 Step 2: Testing API Endpoints..." -ForegroundColor Yellow
Write-Host "==================================" -ForegroundColor Yellow

$headers = @{
    "Authorization" = "Bearer $TOKEN"
    "Content-Type" = "application/json"
}

# Test 1: Dashboard Stats
Write-Host "📊 Testing Dashboard Stats..." -ForegroundColor Cyan
try {
    $response = Invoke-RestMethod -Uri "$BASE_URL/dashboard/stats" -Method Get -Headers $headers
    Write-Host "✅ Dashboard Stats Response:" -ForegroundColor Green
    $response | ConvertTo-Json -Depth 3
} catch {
    Write-Host "❌ Dashboard Stats Error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# Test 2: Classroom Data
Write-Host "🏫 Testing Classroom Data..." -ForegroundColor Cyan
try {
    $response = Invoke-RestMethod -Uri "$BASE_URL/dashboard/classroom" -Method Get -Headers $headers
    Write-Host "✅ Classroom Data Response:" -ForegroundColor Green
    $response | ConvertTo-Json -Depth 3
} catch {
    Write-Host "❌ Classroom Data Error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# Test 3: Progress Data
Write-Host "📈 Testing Progress Data..." -ForegroundColor Cyan
try {
    $response = Invoke-RestMethod -Uri "$BASE_URL/dashboard/progress" -Method Get -Headers $headers
    Write-Host "✅ Progress Data Response:" -ForegroundColor Green
    $response | ConvertTo-Json -Depth 3
} catch {
    Write-Host "❌ Progress Data Error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

Write-Host "✅ API Testing Complete!" -ForegroundColor Green
Write-Host "=======================" -ForegroundColor Green
Write-Host "Check the responses above for any errors." -ForegroundColor White
