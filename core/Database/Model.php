<?php

namespace Core\Database;

use Exception;

abstract class Model
{
	protected string $tableName;

	protected string $primaryKey = 'id';

	protected array $attributes = [
		'id'
	];

	protected Database $db;

	public function __construct()
	{
		$this->db = app(Database::class);
	}

	public function create(array $values): self
	{
		$attrs = $this->attributes;
		unset($attrs[$this->primaryKey]);

		$values = $this->formatInsertValues($attrs, $values);
		$attrs = $this->formatSelectAttributes($attrs);

		$id = $this->db->query("INSERT INTO $this->tableName ($attrs) VALUES ($values)");

		return $this->find($id);
	}

	public function all(): array
	{
		$attrs = $this->formatSelectAttributes();
		return $this->db->query("SELECT $attrs FROM $this->tableName", [], [
			'fetchClass' => $this::class
		]);
	}

	public function find(mixed $value, string $key = ''): mixed
	{
		$attrs = $this->formatSelectAttributes();

		if (empty($key)) {
			$key = $this->primaryKey;
		}

		$query = "SELECT $attrs FROM $this->tableName WHERE $key = :value LIMIT 1";

		$res = $this->db->query(
			$query,
			['value' => $value],
			['fetchClass' => $this::class]
		);

		if (count($res) !== 1) {
			throw new \Exception("Cannot find row in $this->tableName where $key equals $value");
		}

		return $res[0];
	}

	protected function formatInsertValues(array $attrs, array $values): string
	{
		$convertedValues = [];
		foreach ($attrs as $attr) {
			if ($attr === 'created_at') {
				$values[$attr] = date('Y-m-d H:i:s');
			}

			if (array_key_exists($attr, $values)) {
				if (!is_string($values[$attr])) {
					$value = $values[$attr];
				} else {
					$value = "'" . $values[$attr] . "'";
				}
			} else {
				$value = 'NULL';
			}
			$convertedValues[] = $value;
		}

		return implode(',', $convertedValues);
	}

	protected function formatSelectAttributes(array $attributes = []): string
	{
		if (empty($attributes)) {
			$attributes = $this->attributes;
		}

		return implode(',', $attributes);
	}
}