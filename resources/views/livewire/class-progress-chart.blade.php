<canvas id="progressChart"></canvas>
<script>
const ctx = document.getElementById('progressChart');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($classNames),
        datasets: [{
            label: 'Total XP per Class',
            data: @json($xpData),
            backgroundColor: '#4358d0'
        }]
    }
});
</script>
