<style>
  html {
    height: 100%;
  }

  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
    padding: 0;
  }

  .content {
    flex: 1 0 auto;
    padding-bottom: 100px; /* space for fixed footer */
  }

  .footer {
    background-color: #f8f9fa;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    height: 30px;
  }

  .table-responsive {
    width: 100%;
    overflow-x: auto;
  }
</style>
<!-- Fixed Footer -->
<footer class="footer footer-transparent d-print-none fixed-bottom bg-body">
  <div class="container-xl">
    <div class="row text-center align-items-center flex-row-reverse">
      <div class="col-lg-auto ms-lg-auto">
        <ul class="list-inline list-inline-dots mb-0">
          <li class="list-inline-item">
            <a href="https://tabler.io/docs" target="_blank" class="link-secondary" rel="noopener">
              Built using Tabler Admin Template
            </a>
          </li>
        </ul>
      </div>
      <div class="col-12 col-lg-auto mt-3 mt-lg-0">
        <ul class="list-inline list-inline-dots mb-0">
          <li class="list-inline-item text-secondary">
            &copy; 2025 DBAMS  —
            <a href="https://github.com/gggodwin" class="link-secondary">GNS</a>.
            All rights reserved.
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>
