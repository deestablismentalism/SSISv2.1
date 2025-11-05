<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modern Breadcrumb</title>
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background-color: #f9fafb;
      padding: 40px;
    }

    .breadcrumb {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      font-size: 0.95rem;
      gap: 6px;
    }

    .breadcrumb a {
      background: #e8f0fe;
      color: #1a73e8;
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 20px;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .breadcrumb a:hover {
      background: #d2e3fc;
      transform: translateY(-1px);
    }

    .breadcrumb .separator {
      color: #999;
      margin: 0 4px;
      font-weight: bold;
    }

    .breadcrumb .active {
      background: #1a73e8;
      color: #fff;
      cursor: default;
      font-weight: 600;
      pointer-events: none;
    }

    /* Optional: subtle fade animation */
    .breadcrumb {
      animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <nav class="breadcrumb">
    <a href="#">Enrolls</a>
    <span class="separator">›</span>
    <a href="#" class="active">Processed Enrollments</a>
  </nav>

</body>
</html>
