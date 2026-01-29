<?php
defined('ABSPATH') || exit;

return [

    'capabilities' => [
        // Identity
        'bcc_view_profile',
        'bcc_edit_own_profile',
        'bcc_switch_role',

        // Role flags
        'bcc_can_build',
        'bcc_can_validate',
        'bcc_can_create',

        // Contribution
        'bcc_submit_contribution',
        'bcc_edit_own_contribution',
        'bcc_view_contribution_status',

        // Build / Project
        'bcc_build_project',
        'bcc_edit_own_project',
        'bcc_contribute_to_project',

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
            'bcc_can_validate',
            'bcc_view_validation_queue',
            'bcc_validate_contribution',
            'bcc_flag_contribution',
            'bcc_view_reputation',
            'bcc_vote',
        ],

        'bcc_builder' => [
            'bcc_view_profile',
            'bcc_edit_own_profile',
            'bcc_can_build',
            'bcc_submit_contribution',
            'bcc_edit_own_contribution',
            'bcc_view_contribution_status',
            'bcc_build_project',
            'bcc_edit_own_project',
            'bcc_contribute_to_project',
            'bcc_create_proposal',
            'bcc_vote',
            'bcc_view_governance',
            'bcc_view_reputation',
        ],

        'bcc_creator' => [
            'bcc_view_profile',
            'bcc_can_create',
            'bcc_create_asset',
            'bcc_mint_nft',
            'bcc_manage_own_assets',
            'bcc_view_reputation',
        ],

        'bcc_community' => [
            'bcc_view_profile',
            'bcc_submit_contribution',
            'bcc_view_contribution_status',
            'bcc_view_governance',
        ],
    ],
];