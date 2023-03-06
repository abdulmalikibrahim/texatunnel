<?php
class SNMP {
	/* Constants */
	public const int = VERSION_1;
	public const int = VERSION_2c;
	public const int = VERSION_2C;
	public const int = VERSION_3;
	public const int = ERRNO_NOERROR;
	public const int = ERRNO_ANY;
	public const int = ERRNO_GENERIC;
	public const int = ERRNO_TIMEOUT;
	public const int = ERRNO_ERROR_IN_REPLY;
	public const int = ERRNO_OID_NOT_INCREASING;
	public const int = ERRNO_OID_PARSING_ERROR;
	public const int = ERRNO_MULTIPLE_SET_QUERIES;
	/* Properties */
	public readonly array $info;
	public ?int $max_oids;
	public int $valueretrieval;
	public bool $quick_print;
	public bool $enum_print;
	public int $oid_output_format;
	public bool $oid_increasing_check;
	public int $exceptions_enabled;
	/* Methods */
	public __construct(
	    int $version,
	    string $hostname,
	    string $community,
	    int $timeout = -1,
	    int $retries = -1
	)
	public close(): bool
	public get(array|string $objectId, bool $preserveKeys = false): mixed
	public getErrno(): int
	public getError(): string
	public getnext(array|string $objectId): mixed
	public set(array|string $objectId, array|string $type, array|string $value): bool
	public setSecurity(
	    string $securityLevel,
	    string $authProtocol = "",
	    string $authPassphrase = "",
	    string $privacyProtocol = "",
	    string $privacyPassphrase = "",
	    string $contextName = "",
	    string $contextEngineId = ""
	): bool
	public walk(
	    array|string $objectId,
	    bool $suffixAsKey = false,
	    int $maxRepetitions = -1,
	    int $nonRepeaters = -1
	): array|false
	}
	