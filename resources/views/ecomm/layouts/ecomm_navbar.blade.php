<nav class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 0px 20px 0px 20px;">
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('orders.panel.ecomm') }}">Todos os pedidos<span
            class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('orders.invoicesReport.ecomm') }}">Recibos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('orders.settings.ecomm') }}">Configurações</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="font-weight: bold; color: #d26075" href="{{ route('ecomm') }}">Comprar</a>
      </li>
      <li class="nav-item">
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#migratemodal">
          Migrar para o backoffice
        </button>
      </li>
    </ul>
  </div>
</nav>

<div class="modal fade" id="migratemodal" tabindex="-1" aria-labelledby="migratemodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="migratemodalLabel">Migrate</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
        <a href="{{ route('orders.migrate.user.ecomm') }}">
          <button type="button" class="btn btn-outline-success">
            Migrate
          </button>
        </a>
      </div>
    </div>
  </div>
</div>

@if ($errors->any())
  @foreach ($errors->all() as $error)
    <div class="alert alert-danger">
      {{ $error }}
    </div>
  @endforeach
@endif

@if (session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
@endif

@if (session('message'))
  <div class="alert alert-success">
    {{ session('message') }}
  </div>
@endif

<div class="alert alert-alert" id="messagePass">
</div>

@if (session('erro'))
  <div class="alert alert-danger">
    {{ session('erro') }}
  </div>
@endif
