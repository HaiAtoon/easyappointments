<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Centralized field-level encryption service.
 *
 * Maintains the registry of which DB columns are encrypted and provides
 * encrypt_record() / decrypt_record() methods that any model, controller,
 * or library can call. This is the ONLY place encryption field mappings
 * should be defined.
 */
class Field_encryption
{
    /**
     * Map of table name => list of encrypted column names.
     * Table names are WITHOUT the DB prefix (e.g. 'users', not 'ea_users').
     */
    private static array $encrypted_fields = [
        'users' => ['id_number'],
        'documentation_entries' => ['session_summary'],
    ];

    /**
     * Fields that should get a companion _hash column for lookups.
     */
    private static array $hashed_fields = [
        'users' => ['id_number'],
    ];

    /**
     * Internal fields (like _hash columns) that should be stripped from output.
     */
    private static array $internal_fields = [
        'users' => ['id_number_hash'],
    ];

    public static function get_encrypted_fields(string $table): array
    {
        return self::$encrypted_fields[$table] ?? [];
    }

    public static function get_hashed_fields(string $table): array
    {
        return self::$hashed_fields[$table] ?? [];
    }

    /**
     * Encrypt sensitive fields in a record before DB insert/update.
     * Also generates _hash values for fields that need lookup support.
     */
    public static function encrypt_record(string $table, array &$record): void
    {
        foreach (self::get_encrypted_fields($table) as $field) {
            if (!empty($record[$field]) && !str_starts_with($record[$field], 'enc:')) {
                if (in_array($field, self::get_hashed_fields($table))) {
                    $record[$field . '_hash'] = field_hash($record[$field]);
                }

                $record[$field] = field_encrypt($record[$field]);
            }
        }
    }

    /**
     * Decrypt sensitive fields in a record after DB read.
     * Also strips internal fields (like _hash columns) from output.
     */
    public static function decrypt_record(string $table, array &$record): void
    {
        foreach (self::get_encrypted_fields($table) as $field) {
            if (!empty($record[$field])) {
                $record[$field] = field_decrypt($record[$field]);
            }
        }

        foreach (self::$internal_fields[$table] ?? [] as $field) {
            unset($record[$field]);
        }
    }

    /**
     * Decrypt a batch of records.
     */
    public static function decrypt_records(string $table, array &$records): void
    {
        foreach ($records as &$record) {
            self::decrypt_record($table, $record);
        }
    }

    /**
     * Get the hash of a value for lookup queries.
     */
    public static function hash_for_lookup(string $value): ?string
    {
        return field_hash($value);
    }
}
