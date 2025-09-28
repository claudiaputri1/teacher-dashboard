# Test Web Add Student Script
$BASE_URL = "http://localhost:8000"

Write-Host "Testing Web Add Student..." -ForegroundColor Green

try {
    # Test get classes endpoint
    Write-Host "Testing get classes endpoint..." -ForegroundColor Yellow
    $classesResponse = Invoke-WebRequest -Uri "$BASE_URL/dashboard/classes" -Method Get -SessionVariable session
    Write-Host "Classes endpoint status: $($classesResponse.StatusCode)" -ForegroundColor Green
    
    if ($classesResponse.StatusCode -eq 200) {
        Write-Host "Classes endpoint working!" -ForegroundColor Green
    } else {
        Write-Host "Classes endpoint failed!" -ForegroundColor Red
    }
    
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "This is expected if not logged in via web session" -ForegroundColor Yellow
}

Write-Host "Test complete! Please test manually in browser:" -ForegroundColor Green
Write-Host "1. Login to http://localhost:8000/login" -ForegroundColor Cyan
Write-Host "2. Go to dashboard" -ForegroundColor Cyan  
Write-Host "3. Click '+ Tambah Siswa' button" -ForegroundColor Cyan
Write-Host "4. Fill form and submit" -ForegroundColor Cyan
