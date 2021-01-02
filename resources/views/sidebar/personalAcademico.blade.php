<div class="sidebar">
    <nav class="sidebar-nav ps ps--active-y">
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link active" href="#">
            @if(Auth::user()->rol===3)
            <i class="nav-icon fa fa-user"></i>Docente :
            @else
            <i class="nav-icon fa fa-user"></i>Auxiliar :
            @endif
            <div class="container ml-3">
              <p class="mt-2 mb-0">{{Auth::user()->nombre}}</p>
              <p class="">{{Auth::user()->apellido}}</p>
            </div>
            {{-- <span class="badge badge-info">NEW</span> --}}
          </a>
        </li>
        <li class="nav-title">menu</li>

        <li class="nav-item">
        <a class="nav-link" href="{{url('asistencias')}}">
            <i class="nav-icon fa fa-list"></i> Asistencias</a>
        </li>

        {{-- <li class="nav-item">
        <a class="nav-link" href="{{url('unidadacademica')}}">
            <i class="nav-icon fa fa-pencil"></i> Unidad Academica</a>
        </li> --}}
        {{-- <li class="nav-title">Components</li> --}}
        
   
        
      
       
      
      </ul>
      <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
      </div>
      <div class="ps__rail-y" style="top: 0px; height: 901px; right: 0px;">
        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 681px;"></div>
      </div>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
  </div>