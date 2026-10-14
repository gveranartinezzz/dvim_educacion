@extends('layouts.app')

@section('title', 'Centro DIVM Educación Especial - DIVM')


@section('content')
    
    
    

    
    

    

    <!-- Aquí puedes colocar tu menú HTML si ya no quieres usar PHP -->

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="card-divm mb-4">

                    <div class="header-divm text-center py-4 mb-4">
                        <h1 class="display-5 fw-bold">Centro DIVM</h1>
                        <h4 class="mb-0">
                            Educación Especial - San Martín Texmelucan, Puebla
                        </h4>
                    </div>

                    <div class="row">

                        <!-- INFORMACIÓN -->
                        <div class="col-md-6">

                            <span class="section-label">
                                <i class="bi bi-person-badge-fill"></i>
                                Dirección General
                            </span>

                            <p class="fs-5 fw-bold text-divm">
                                Dalia Irene Vera Martínez
                            </p>

                            <p>
                                Liderando con compromiso la educación inclusiva
                                y el desarrollo integral de nuestra comunidad estudiantil.
                            </p>

                            <span class="section-label">
                                <i class="bi bi-geo-alt-fill"></i>
                                Ubicación
                            </span>

                            <p>
                                <strong>Domicilio:</strong><br>
                                C. Ajusco 46, Col. Linda Vista,<br>
                                74070, San Martín Texmelucan de Labastida, Pue.
                            </p>

                            <a href="https://www.google.com/maps/search/?api=1&query=C.+Ajusco+46,+Col.+Linda+Vista,+74070+San+Martín+Texmelucan+de+Labastida,+Pue."
                               target="_blank"
                               class="btn btn-outline-primary btn-sm mb-4">

                                <i class="bi bi-map"></i>
                                Ver en Google Maps

                            </a>

                        </div>

                        <!-- MAPA -->
                        <div class="col-md-6">

                            <span class="section-label">
                                <i class="bi bi-map-fill"></i>
                                Ubicación en el Mapa
                            </span>

                            <div class="ratio ratio-16x9">

                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3772.5802269558917!2d-98.4414349!3d19.280145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85cfd13778a4b49b%3A0x6b777a83d340b377!2sCalle+Ajusco+46%2C+Linda+Vista%2C+74070+San+Mart%C3%ADn+Texmelucan+de+Labastida%2C+Pue.!5e0!3m2!1ses-419!2smx!4v1715870000000!5m2!1ses-419!2smx"
                                    style="border:0; border-radius:10px;"
                                    allowfullscreen=""
                                    loading="lazy">
                                </iframe>

                            </div>

                        </div>

                    </div>

                    <!-- MENSAJE -->
                    <div class="mt-4 p-3 rounded" style="background-color: #fceaf6;">

                        <p class="mb-0 text-center fw-bold text-divm">

                            <i class="bi bi-star-fill"></i>
                            Bienvenidos al Sistema de Gestión del Centro DIVM

                        </p>

                    </div>

                </div>

            </div>
        </div>
    </div>

    
@endsection
