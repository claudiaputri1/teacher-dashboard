<x-filament::page>
    <canvas id="progressChart"></canvas>
</x-filament::page>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    },
});
</script>
@endpush
