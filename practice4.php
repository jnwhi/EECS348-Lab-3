<?php
// Retrieve and validate input size (default to 10, clamped between 1 and 25)
$rawSize = filter_input(INPUT_POST, 'size', FILTER_VALIDATE_INT) ?? filter_input(INPUT_GET, 'size', FILTER_VALIDATE_INT) ?? 10;
$size = max(1, min(25, (int)$rawSize));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Practice 4</title>
  <style>
    /* ==========================================================================
       Swiss Color-Block Tokens & Global Reset (§2.1, §4.2)
       ========================================================================== */
    :root {
      --ink: #111111;
      --paper: #f5f0e6;
      --cobalt: #1f3fd6;
      --coral: #ff5a4e;
      --mustard: #f2c218;
      --mint: #3fd4a6;
      --muted: #d9d3c6;

      --font: "Helvetica Neue", Helvetica, Arial, "Inter", sans-serif;
      --ease: cubic-bezier(0.2, 0.8, 0.2, 1);
      --pad-x: clamp(1.25rem, 5vw, 4rem);
      --pad-y: clamp(2rem, 6vw, 4rem);
    }

    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      border-radius: 0;
    }

    html {
      -webkit-text-size-adjust: 100%;
    }

    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      font-family: var(--font);
      color: var(--ink);
      background: var(--paper);
      line-height: 1.4;
      -webkit-font-smoothing: antialiased;
    }

    ::selection {
      background: var(--mustard);
      color: var(--ink);
    }

    .label {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.28em;
      line-height: 1;
    }

    /* ==========================================================================
       Hero Section (§5.1)
       ========================================================================== */
    .hero {
      background: var(--ink);
      color: var(--paper);
      padding: var(--pad-y) var(--pad-x);
      display: grid;
      gap: 1.25rem;
    }

    .hero .label {
      color: var(--mustard);
    }

    .hero h1 {
      font-size: clamp(2.5rem, 8vw, 6.5rem);
      line-height: 0.9;
      letter-spacing: -0.05em;
      font-weight: 800;
      text-transform: uppercase;
      max-width: 14ch;
    }

    .hero .support {
      font-size: clamp(1rem, 1.4vw, 1.15rem);
      color: var(--muted);
      max-width: 52ch;
    }

    /* ==========================================================================
       Form Control Block (§5.5, §9)
       ========================================================================== */
    main {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .control-block {
      background: var(--paper);
      padding: var(--pad-y) var(--pad-x);
      border-bottom: 4px solid var(--ink);
    }

    .matrix-form {
      display: flex;
      flex-wrap: wrap;
      align-items: flex-end;
      gap: 1.5rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .form-group label {
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 0.15em;
      text-transform: uppercase;
    }

    .form-group .hint {
      font-size: 0.75rem;
      color: #666;
    }

    .form-group input {
      height: 54px;
      min-width: 200px;
      font-family: var(--font);
      font-size: 1.1rem;
      font-weight: 600;
      padding: 0 1rem;
      background: #ffffff;
      color: var(--ink);
      border: 3px solid var(--ink);
      outline: none;
      transition: outline 150ms var(--ease);
    }

    .form-group input:focus-visible {
      outline: 4px solid var(--cobalt);
      outline-offset: 2px;
    }

    .btn-submit {
      height: 54px;
      font-family: var(--font);
      font-size: 0.9rem;
      font-weight: 700;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      padding: 0 2rem;
      background: var(--ink);
      color: var(--paper);
      border: 3px solid var(--ink);
      cursor: pointer;
      transition: background-color 200ms var(--ease), color 200ms var(--ease);
    }

    .btn-submit:hover {
      background: var(--cobalt);
      color: var(--paper);
    }

    .btn-submit:focus-visible {
      outline: 4px solid var(--cobalt);
      outline-offset: 3px;
    }

    /* ==========================================================================
       Table Presentation (§9)
       ========================================================================== */
    .table-container {
      background: var(--paper);
      padding: var(--pad-y) var(--pad-x);
      overflow-x: auto;
    }

    .matrix-table {
      width: 100%;
      border-collapse: collapse;
      border-top: 4px solid var(--ink);
      border-bottom: 4px solid var(--ink);
      font-variant-numeric: tabular-nums;
    }

    .matrix-table th,
    .matrix-table td {
      padding: 0.75rem 1rem;
      text-align: right;
      font-size: clamp(0.9rem, 1.2vw, 1.1rem);
      border-bottom: 1px solid var(--ink);
      border-right: 1px solid var(--ink);
      white-space: nowrap;
    }

    .matrix-table tr > *:last-child {
      border-right: none;
    }

    /* Header Row: Column Indexes */
    .matrix-table thead th {
      background: var(--ink);
      color: var(--paper);
      font-weight: 800;
      border-bottom: 2px solid var(--paper);
    }

    /* First Column: Row Indexes */
    .matrix-table tbody th {
      background: var(--ink);
      color: var(--paper);
      font-weight: 800;
      text-align: center;
      border-right: 2px solid var(--paper);
    }

    /* Top-Left Coordinate Intersect */
    .matrix-table thead .origin {
      background: var(--cobalt);
      color: var(--paper);
      text-align: center;
      font-size: 1.25rem;
    }

    .matrix-table tbody td {
      background: #ffffff;
      color: var(--ink);
      font-weight: 500;
    }

    /* ==========================================================================
       Footer (§5.3)
       ========================================================================== */
    footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      padding: 1.25rem var(--pad-x);
      background: var(--paper);
      color: var(--ink);
      border-top: 4px solid var(--ink);
    }

    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation: none !important;
        transition: none !important;
      }
    }
/* Universal Back Link */
.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  width: fit-content;
  text-decoration: none;
  background: transparent;
  color: var(--paper);
  border: 2px solid var(--paper);
  border-radius: 0;
  padding: 0.45rem 0.9rem;
  font-family: var(--font);
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.28em;
  text-transform: uppercase;
  cursor: pointer;
  transition: background-color 200ms var(--ease), color 200ms var(--ease), border-color 200ms var(--ease);
}

.back-btn .arrow {
  font-size: 1rem;
  line-height: 1;
  transition: transform 200ms var(--ease);
}

.back-btn:hover {
  background: var(--mustard);
  color: var(--ink);
  border-color: var(--mustard);
}

.back-btn:hover .arrow {
  transform: translateX(-4px);
}

.back-btn:focus-visible {
  outline: 4px solid var(--mustard);
  outline-offset: 4px;
}
  </style>
</head>
<body>

  <!-- Hero Component -->
  <header class="hero">
  <a href="index.html" class="back-btn">
    <span class="arrow" aria-hidden="true">←</span>
    <span>RETURN</span>
  </a>
    <p class="label">Practice 4 | JUSTIN WHITAKER</p>
    <h1>MULTIPLICATION<br>MATRIX</h1>
    <p class="support">Generate dynamic factor tables with indexed row and column coordinates. Formatted with ink rules and contrast headers[cite: 1].</p>
  </header>

  <main>
    <!-- Size Input Form -->
    <section class="control-block" aria-label="Table Configuration">
      <form method="POST" action="" class="matrix-form">
        <div class="form-group">
          <label for="size">Matrix Dimension ($N \times N$)</label>
          <input 
            type="number" 
            id="size" 
            name="size" 
            value="<?= htmlspecialchars((string)$size, ENT_QUOTES, 'UTF-8') ?>" 
            min="1" 
            max="25" 
            required
          >
          <span class="hint">Input integer dimension between 1 and 25</span>
        </div>
        <button type="submit" class="btn-submit">Generate Table</button>
      </form>
    </section>

    <!-- Rendered Multiplication Table -->
    <section class="table-container" aria-label="Matrix Result">
      <table class="matrix-table">
        <thead>
          <tr>
            <th class="origin" aria-label="Multiplication sign">&times;</th>
            <?php for ($col = 1; $col <= $size; $col++): ?>
              <th scope="col"><?= $col ?></th>
            <?php endfor; ?>
          </tr>
        </thead>
        <tbody>
          <?php for ($row = 1; $row <= $size; $row++): ?>
            <tr>
              <th scope="row"><?= $row ?></th>
              <?php for ($col = 1; $col <= $size; $col++): ?>
                <td><?= $row * $col ?></td>
              <?php endfor; ?>
            </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <span class="label">PHP MATRIX GENERATOR</span>
    <span class="label">GRID // <?= $size ?>&times;<?= $size ?></span>
  </footer>

</body>
</html>