<nav class="navbar navbar-expand-lg bg-light  " >
        <div class="container-fluid" >
          <a class="navbar-brand text-warning " href="#">New York Blogs</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent" onclick="nav()">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/report">Reports</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Dropdown
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">News</a></li>
                  <li><a class="dropdown-item" href="#">Sports</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Movies</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" aria-disabled="true">Disabled</a>
              </li>
            </ul>

            <form class="d-flex" role="search" action="{{ route('blog.search') }}" method="GET">
    <input class="form-control me-2 input" type="search" placeholder="Search" name="name" aria-label="Search" style="width:200px;">
    <button class="btn btn-outline-success" type="submit">Search</button>
    @error('name')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</form>


          </div>
        </div>
      </nav>