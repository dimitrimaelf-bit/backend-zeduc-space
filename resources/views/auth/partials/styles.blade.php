<style>
    :root {
        --miam-black: #000000;
        --miam-white: #ffffff;
        --miam-grey: #6b6b6b;
        --miam-border: #000000;
    }

    .auth-header {
        background-color: var(--miam-black);
        color: var(--miam-white);
    }

    .auth-brand {
        color: var(--miam-white);
        font-size: .85rem;
        letter-spacing: .2em;
        text-transform: uppercase;
    }

    .auth-page {
        position: relative;
        min-height: calc(100vh - 60px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        background: url("{{ asset('images/boisson_accueil_jpg.jpeg') }}") center / cover no-repeat;
    }

    .auth-page::before {
        content: "";
        position: absolute;
        inset: 0;
        background-color: rgba(255, 255, 255, .88);
    }

    .auth-card {
        position: relative;
        width: 100%;
        max-width: 420px;
    }

    .auth-tabs {
        display: flex;
        margin-bottom: 2.5rem;
    }

    .auth-tabs a {
        flex: 1;
        text-align: center;
        padding: .75rem;
        border: 1px solid var(--miam-border);
        color: var(--miam-black);
        text-decoration: none;
        letter-spacing: .05em;
        font-size: .9rem;
        background-color: var(--miam-white);
    }

    .auth-tabs a.active {
        background-color: var(--miam-black);
        color: var(--miam-white);
    }

    .auth-title {
        font-family: Georgia, "Times New Roman", serif;
        font-weight: 400;
        font-size: 2rem;
        color: var(--miam-black);
    }

    .auth-subtitle {
        color: var(--miam-grey);
        font-size: .9rem;
    }

    .auth-card .form-label {
        font-size: .8rem;
        font-weight: 600;
        color: var(--miam-black);
    }

    .auth-card .form-control,
    .auth-card .form-select {
        border: 1px solid var(--miam-border);
        border-radius: 0;
        background-color: var(--miam-white);
        padding: .7rem .8rem;
        box-shadow: none;
    }

    .auth-card .form-control:focus,
    .auth-card .form-select:focus {
        border-color: var(--miam-black);
        box-shadow: 0 0 0 .15rem rgba(0, 0, 0, .15);
    }

    .btn-miam {
        background-color: var(--miam-black);
        color: var(--miam-white);
        border: 1px solid var(--miam-black);
        border-radius: 0;
        padding: .75rem;
        letter-spacing: .05em;
    }

    .btn-miam:hover,
    .btn-miam:focus {
        background-color: #222222;
        color: var(--miam-white);
    }

    .auth-link {
        color: var(--miam-black);
        text-decoration: underline;
        font-size: .8rem;
    }

    .auth-link:hover {
        color: var(--miam-grey);
    }
</style>