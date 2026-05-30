<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Entra Group → ApiSpi Role Mapping
    |--------------------------------------------------------------------------
    | Keys are Entra group display names OR object IDs.
    | Values are ApiSpi role strings stored in users.azure_roles.
    | Use 'admin' to grant is_admin on login.
    |
    | Example:
    |   AZURE_ADMIN_GROUP="ApiSpi Admins"
    |   AZURE_AGENT_GROUP="ApiSpi Agents"
    */
    'role_map' => array_filter([
        env('AZURE_ADMIN_GROUP', '') => 'admin',
        env('AZURE_AGENT_GROUP', '') => 'agent_user',
    ]),

    /*
    |--------------------------------------------------------------------------
    | Sync Admin Flag
    |--------------------------------------------------------------------------
    | When true, is_admin is removed if the user is NOT in the admin group.
    | When false (default), admin can also be granted manually and Azure only adds.
    */
    'sync_admin' => (bool) env('AZURE_SYNC_ADMIN', false),

];
