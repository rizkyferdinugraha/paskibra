@php
    use Carbon\Carbon;
    $month = (int) ($currentMonth ?? now()->month);
    $year = (int) ($currentYear ?? now()->year);
    $firstDay = Carbon::create($year, $month, 1);
    $startOfCalendar = $firstDay->copy()->startOfWeek(Carbon::MONDAY);
    $endOfMonth = $firstDay->copy()->endOfMonth();
    $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

    $days = [];
    $cursor = $startOfCalendar->copy();
    while ($cursor->lte($endOfCalendar)) {
        $days[] = $cursor->copy();
        $cursor->addDay();
    }

    // Grouping per hari: untuk event multi-hari, isi di setiap tanggal dari start..end
    $eventsByDate = collect();
    foreach (collect($events ?? []) as $e) {
        $start = $e->waktu_mulai ? Carbon::parse($e->waktu_mulai) : Carbon::parse($e->tanggal);
        $end = $e->waktu_selesai ? Carbon::parse($e->waktu_selesai) : $start->copy();
        $cursor = $start->copy()->startOfDay();
        $last = $end->copy()->startOfDay();
        while ($cursor->lte($last)) {
            $key = $cursor->toDateString();
            $eventsByDate[$key] = ($eventsByDate[$key] ?? collect())->push($e);
            $cursor->addDay();
        }
    }

    $prev = $firstDay->copy()->subMonth();
    $next = $firstDay->copy()->addMonth();
    $isToday = function(Carbon $d){ return $d->isToday(); };
@endphp

@if(!empty($withAssets))
    <style>
        .calendar-container{width:100%;}
        .calendar-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
        .calendar-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:6px}
        .calendar-day{border:1px solid #e5e7eb;border-radius:8px;padding:8px;min-height:90px;position:relative;background:#fff}
        .calendar-day.out{background:#fafafa;color:#9ca3af}
        .calendar-day.today{border-color:#1976d2}
        .calendar-weekday{font-weight:600;color:#6b7280;text-transform:uppercase;font-size:11px}
        .calendar-date{font-weight:600;font-size:12px}
        .calendar-events{margin-top:6px}
        .calendar-event{display:block;font-size:12px;padding:4px 6px;border-radius:6px;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .calendar-event.wajib{background:#fee2e2;color:#991b1b}
        .calendar-event.normal{background:#e0f2fe;color:#075985}
        .calendar-navigation .btn{padding:6px 10px}
        .calendar-nav-btn{display:inline-flex;align-items:center;gap:6px}
        .calendar-nav-btn .bi{font-size:14px}
        .calendar-nav-prev{border-color:#6b7280;color:#374151}
        .calendar-nav-next{border-color:#6b7280;color:#374151}
        .calendar-nav-prev:hover,.calendar-nav-next:hover{background:#f3f4f6}
        .calendar-nav-btn span{font-weight:700;color:#111827}
        .calendar-header .fw-bold{color:#111827;font-weight:700}
    </style>
@endif

<div class="calendar-container">
    <div class="calendar-header">
        <div class="calendar-navigation">
            <a class="btn btn-sm btn-outline-secondary calendar-nav-btn calendar-nav-prev" href="?month={{ $prev->month }}&year={{ $prev->year }}">
                <i class="bi bi-chevron-left"></i>
                <span>Sebelumnya</span>
                <small class="text-muted d-none d-sm-inline">({{ $prev->isoFormat('MMM YYYY') }})</small>
            </a>
        </div>
        <div class="fw-bold">{{ $firstDay->isoFormat('MMMM YYYY') }}</div>
        <div class="calendar-navigation">
            <a class="btn btn-sm btn-outline-secondary calendar-nav-btn calendar-nav-next" href="?month={{ $next->month }}&year={{ $next->year }}">
                <span>Berikutnya</span>
                <small class="text-muted d-none d-sm-inline">({{ $next->isoFormat('MMM YYYY') }})</small>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <div class="calendar-grid mb-2">
        @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $wd)
            <div class="calendar-weekday text-center">{{ $wd }}</div>
        @endforeach
    </div>

    <div class="calendar-grid">
        @foreach($days as $d)
            @php
                $key = $d->toDateString();
                $list = $eventsByDate->get($key, collect());
                $out = $d->month !== $month;
            @endphp
            <div class="calendar-day {{ $out ? 'out' : '' }} {{ $isToday($d) ? 'today' : '' }}" data-date="{{ $key }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="calendar-date">{{ $d->day }}</div>
                    @if($isToday($d))
                        <span class="badge bg-primary">Hari ini</span>
                    @endif
                </div>
                <div class="calendar-events">
                    @foreach($list as $ev)
                        @php
                            $wajib = $ev->wajibHadir->contains('id', auth()->id());
                            $mulai = $ev->waktu_mulai ?? \Carbon\Carbon::parse($ev->tanggal);
                            $selesai = $ev->waktu_selesai;
                        @endphp
                        <a href="{{ route('acara.show', $ev) }}" class="calendar-event {{ $wajib ? 'wajib' : 'normal' }}" data-bs-toggle="tooltip" title="{{ $ev->nama }} - {{ $mulai->format('d/m H:i') }}@if($selesai) - {{ ($selesai->isSameDay($mulai) ? $selesai->format('H:i') : $selesai->format('d/m H:i')) }}@endif | {{ $ev->lokasi }}">
                            {{ $ev->nama }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

