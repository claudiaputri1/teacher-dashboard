#!/bin/bash

# API Testing Script untuk Teacher Dashboard
# Pastikan server Laravel berjalan di http://localhost:8000

BASE_URL="http://localhost:8000/api"
EMAIL="your-email@example.com"
PASSWORD="your-password"

echo "🚀 Starting API Tests for Teacher Dashboard"
echo "=========================================="

# Step 1: Generate API Token
echo "📝 Step 1: Generating API Token..."
TOKEN_RESPONSE=$(curl -s -X POST \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"$EMAIL\",\"password\":\"$PASSWORD\"}" \
  $BASE_URL/auth/token)

echo "Token Response: $TOKEN_RESPONSE"

# Extract token from response (requires jq)
if command -v jq &> /dev/null; then
    TOKEN=$(echo $TOKEN_RESPONSE | jq -r '.token')
    echo "✅ Token generated: ${TOKEN:0:20}..."
else
    echo "⚠️  jq not installed. Please extract token manually from response above."
    echo "Please set TOKEN variable manually:"
    read -p "Enter your token: " TOKEN
fi

if [ -z "$TOKEN" ] || [ "$TOKEN" = "null" ]; then
    echo "❌ Failed to get token. Please check credentials."
    exit 1
fi

echo ""
echo "🧪 Step 2: Testing API Endpoints..."
echo "=================================="

# Test 1: Dashboard Stats
echo "📊 Testing Dashboard Stats..."
curl -s -H "Authorization: Bearer $TOKEN" \
     -H "Content-Type: application/json" \
     $BASE_URL/dashboard/stats | jq '.' || echo "Response received"

echo ""

# Test 2: Classroom Data
echo "🏫 Testing Classroom Data..."
curl -s -H "Authorization: Bearer $TOKEN" \
     -H "Content-Type: application/json" \
     $BASE_URL/dashboard/classroom | jq '.' || echo "Response received"

echo ""

# Test 3: Progress Data
echo "📈 Testing Progress Data..."
curl -s -H "Authorization: Bearer $TOKEN" \
     -H "Content-Type: application/json" \
     $BASE_URL/dashboard/progress | jq '.' || echo "Response received"

echo ""

# Test 4: Assessment Data
echo "🤖 Testing Assessment Data..."
curl -s -H "Authorization: Bearer $TOKEN" \
     -H "Content-Type: application/json" \
     $BASE_URL/dashboard/assessment | jq '.' || echo "Response received"

echo ""

# Test 5: Assignment Data
echo "📝 Testing Assignment Data..."
curl -s -H "Authorization: Bearer $TOKEN" \
     -H "Content-Type: application/json" \
     $BASE_URL/dashboard/assignments | jq '.' || echo "Response received"

echo ""

# Test 6: Create Class
echo "➕ Testing Create Class..."
curl -s -X POST \
     -H "Authorization: Bearer $TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"name":"XII IPA 3","academic_year":"2025/2026"}' \
     $BASE_URL/classes | jq '.' || echo "Response received"

echo ""

# Test 7: List Classes
echo "📋 Testing List Classes..."
curl -s -H "Authorization: Bearer $TOKEN" \
     -H "Content-Type: application/json" \
     $BASE_URL/classes | jq '.' || echo "Response received"

echo ""

# Test 8: Create Student
echo "👨‍🎓 Testing Create Student..."
curl -s -X POST \
     -H "Authorization: Bearer $TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"name":"Test Student","email":"student@test.com","nis":"12345"}' \
     $BASE_URL/students | jq '.' || echo "Response received"

echo ""

# Test 9: List Students
echo "👥 Testing List Students..."
curl -s -H "Authorization: Bearer $TOKEN" \
     -H "Content-Type: application/json" \
     $BASE_URL/students | jq '.' || echo "Response received"

echo ""
echo "✅ API Testing Complete!"
echo "======================="
echo "Check the responses above for any errors."
echo "If you see JSON responses, the APIs are working correctly."
