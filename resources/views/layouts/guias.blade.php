{{-- Panel "Guías e Indicadores" (se usa igual en todos los módulos).
     Variables: $titulo, $intro (opcional),
                $indicadores = [[etiqueta, valor, 'bi-icono'], ...]   (números en vivo)
                $guias       = [[titulo, 'bi-icono', ['punto 1', 'punto 2', ...]], ...] --}}
@php
    $indicadores = $indicadores ?? [];
    $guias       = $guias ?? [];
@endphp
<div class="card-table mb-4">

    <span class="section-label">
        <i class="bi bi-journal-bookmark-fill me-2"></i>{{ $titulo ?? 'Guías e Indicadores' }}
    </span>

    @if(!empty($intro))
        <p class="text-muted">{{ $intro }}</p>
    @endif

    @if(count($indicadores))
        <div class="indicadores mb-4">
            @foreach($indicadores as [$etiqueta, $valor, $icono])
                <div class="indicador">
                    <i class="bi {{ $icono }}"></i>
                    <div class="indicador-valor">{{ $valor }}</div>
                    <div class="indicador-etiqueta">{{ $etiqueta }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="row g-3">
        @foreach($guias as [$tit, $ico, $items])
            <div class="col-md-4">
                <div class="card h-100 guia-card">
                    <div class="card-header"><i class="bi {{ $ico }} me-1"></i>{{ $tit }}</div>
                    <div class="card-body small">
                        <ul class="mb-0">
                            @foreach($items as $it)
                                <li>{!! $it !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
