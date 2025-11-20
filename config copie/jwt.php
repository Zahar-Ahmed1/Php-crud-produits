<?php
/**
 * Configuration JWT
 */

class JWTConfig {
    // Clé secrète pour signer les tokens (changez-la en production)
    const SECRET_KEY = "votre_cle_secrete_tres_longue_et_aleatoire_changez_la_en_production_2024";
    
    // Durée de vie du token (en secondes) - 24 heures
    const TOKEN_EXPIRY = 86400;
    
    // Algorithme de signature
    const ALGORITHM = "HS256";
}

