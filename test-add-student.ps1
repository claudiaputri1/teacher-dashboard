# Test Add Student Script
$BASE_URL = "http://localhost:8000/api"
$EMAIL = "ahmad@test.com"
$PASSWORD = "password"

Write-Host "Testing Add Student API..." -ForegroundColor Green

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
    
    # Test Get Classes
    Write-Host "Testing get classes..." -ForegroundColor Yellow
    $headers = @{
        "Authorization" = "Bearer $TOKEN"
        "Content-Type" = "application/json"
    }
    
    $classesResponse = Invoke-RestMethod -Uri "$BASE_URL/classes" -Method Get -Headers $headers
    Write-Host "Classes response:" -ForegroundColor Green
    $classesResponse | ConvertTo-Json
    
    # Test Add Student
    Write-Host "Testing add student..." -ForegroundColor Yellow
    $studentBody = @{
        name = "Test Student Baru"
        nis = "12345999"
        email = "teststudent@example.com"
        class_id = if ($classesResponse.Count -gt 0) { $classesResponse[0].id } else { $null }
    } | ConvertTo-Json
    
    $studentResponse = Invoke-RestMethod -Uri "$BASE_URL/students" -Method Post -Body $studentBody -Headers $headers
    Write-Host "Add student response:" -ForegroundColor Green
    $studentResponse | ConvertTo-Json
    
    # Test Get Students
    Write-Host "Testing get students..." -ForegroundColor Yellow
    $studentsResponse = Invoke-RestMethod -Uri "$BASE_URL/students" -Method Get -Headers $headers
    Write-Host "Students response:" -ForegroundColor Green
    $studentsResponse | ConvertTo-Json
    
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Response Body: $responseBody" -ForegroundColor Red
    }
}

Write-Host "Test complete!" -ForegroundColor Green
