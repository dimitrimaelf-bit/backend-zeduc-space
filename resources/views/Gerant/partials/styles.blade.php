/* Core / Bootstrap Customization */
@import 'bootstrap/dist/css/bootstrap.min.css';

/* Vos variables CSS personnalisées */
:root {
    --zeduc-gold: #cfbd97;
    --zeduc-gold-hover: #bfae88;
    --zeduc-black: #0f0f0f;
}

/* Polices et styles globaux */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    color: var(--zeduc-black);
}

/* Classes utilitaires personnalisées */
.text-gold { 
    color: var(--zeduc-gold) !important; 
}

.bg-gold { 
    background-color: var(--zeduc-gold) !important; 
}

/* Boutons personnalisés aux couleurs de l'enseigne */
.btn-gold {
    background-color: var(--zeduc-gold);
    border: 1px solid var(--zeduc-gold);
    color: var(--zeduc-black);
    font-weight: 600;
    transition: background-color 0.2s ease, border-color 0.2s ease;
}

.btn-gold:hover {
    background-color: var(--zeduc-gold-hover);
    border-color: var(--zeduc-gold-hover);
    color: var(--zeduc-black);
}