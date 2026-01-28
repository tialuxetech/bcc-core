<?php
defined( 'ABSPATH' ) || exit;

return [

    'capabilities' => [
        // Identity
        'bcc_view_profile',
        'bcc_edit_own_profile',
        'bcc_switch_role',

        // Contribution
        'bcc_submit_contribution',
        'bcc_edit_own_contribution',
        'bcc_view_contribution_status',

        // Validation
        'bcc_view_validation_queue',
        'bcc_validate_contribution',
        'bcc_flag_contribution',

        // Creation / NFT
        'bcc_create_asset',
        'bcc_mint_nft',
        'bcc_manage_own_assets',

        // Governance
        'bcc_create_proposal',
        'bcc_vote',
        'bcc_view_governance',

        // Reputation
        'bcc_view_reputation',
        'bcc_affect_reputation',
    ],

    'role_map' => [

        'bcc_validator' => [
            'bcc_view_profile',
            'bcc_view_validation_queue',
            'bcc_validate_contribution',
            'bcc_flag_contribution',
            'bcc_view_reputation',
            'bcc_vote',
        ],

        'bcc_builder' => [
            'bcc_view_profile',
            'bcc_edit_own_profile',
            'bcc_submit_contribution',
            'bcc_edit_own_contribution',
            'bcc_view_contribution_status',
            'bcc_view_validation_queue',
            'bcc_validate_contribution',
            'bcc_flag_contribution',
            'bcc_create_proposal',
            'bcc_vote',
            'bcc_view_governance',
            'bcc_view_reputation',
        ],

        'bcc_community' => [
            'bcc_view_profile',
            'bcc_submit_contribution',
            'bcc_view_contribution_status',
            'bcc_view_governance',
        ],

        'bcc_creator' => [
            'bcc_view_profile',
            'bcc_create_asset',
            'bcc_mint_nft',
            'bcc_manage_own_assets',
            'bcc_view_reputation',
        ],
    ],
];