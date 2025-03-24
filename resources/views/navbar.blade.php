<div class="sidebar d-flex flex-column">
            
<a class="navbar-brand text-white fs-2 d-flex align-items-center" href="#">
        <img src="{{ asset('images/utn-logo.png') }}" alt="UTN Logo" class="navbar-logo me-2">
        UTNPay
    </a>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('alumnos.index') }}"><i class="bi bi-people"></i> Alumnos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cursos.index') }}"><i class="bi bi-book"></i> Cursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"  href="{{ route('pagos.index') }}"><i class="bi bi-cash"></i> Pagos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('alertas.index') }}"><i class="bi bi-bell"></i> Alertas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('certificados.index') }}"><i class="bi bi-book"></i>Certificados</a>
<               </li>
            </ul>
        </div>