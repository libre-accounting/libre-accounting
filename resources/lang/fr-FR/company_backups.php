<?php

return [

    'export'            => 'Exporter / Sauvegarder',
    'import'            => 'Importer une sauvegarde',
    'restoring'         => 'Restauration…',
    'switch_to_company' => 'Basculer vers l\'entreprise',

    'import_title'       => 'Restaurer à partir d\'une sauvegarde Libre Accounting',
    'import_description' => 'Téléversez une archive de sauvegarde (.zip) exportée depuis Libre Accounting pour recréer l\'entreprise sur cette instance.',
    'import_action'      => 'Restaurer la sauvegarde',

    'messages' => [
        'export_started'    => 'Sauvegarde démarrée. Vous serez notifié lorsque le téléchargement sera prêt.',
        'exporting'         => 'Création de la sauvegarde de l\'entreprise…',
        'export_completed'  => 'La sauvegarde de l\'entreprise est prête.',
        'import_started'    => 'Restauration démarrée. Cela peut prendre un certain temps pour les sauvegardes volumineuses.',
        'importing'         => 'Restauration de l\'entreprise…',
        'import_completed'  => 'L\'entreprise a été restaurée avec succès.',
        'failed'            => 'Une erreur s\'est produite.',
    ],

    'warnings' => [
        'title'                 => 'La restauration s\'est terminée avec quelques remarques :',
        'disabled_modules'      => 'Ces modules sont référencés par la sauvegarde mais ne sont pas installés ici ; ils ont donc été laissés désactivés : :modules.',
        'skipped_reports'       => ':count rapport(s) ont été ignorés car leur type de rapport n\'est pas installé sur cette instance.',
        'unbundled_media'       => ':count fichier(s) étaient stockés sur un stockage distant et n\'ont pas été inclus ; téléversez-les à nouveau manuellement.',
        'missing_currency_refs' => ':count enregistrement(s) référençaient une devise absente de la sauvegarde et ont utilisé la devise par défaut.',
    ],

    'errors' => [
        'invalid_format' => 'Le fichier téléversé n\'est pas une sauvegarde d\'entreprise Libre Accounting valide.',
        'newer_version'  => 'Cette sauvegarde a été créée par une version plus récente de Libre Accounting. Veuillez mettre à jour cette instance avant de restaurer.',
    ],

];
