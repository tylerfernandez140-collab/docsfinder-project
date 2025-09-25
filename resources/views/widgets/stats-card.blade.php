<div class="small-box bg-{{ $color ?? 'info' }}">
    <div class="inner">
        <h3>{{ $value }}</h3>
        <p>{{ $title }}</p>
    </div>
    <div class="icon">
        <i class="{{ $icon ?? 'fas fa-chart-pie' }}"></i>
    </div>
    <a href="{{ $route ?? '#' }}" class="small-box-footer">
        {{ $subtitle }} <i class="fas fa-arrow-circle-right"></i>
    </a>
</div>