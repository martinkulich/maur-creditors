<?php


/**
 * This class defines the structure of the 'settlement' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class SettlementTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.SettlementTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('settlement');
		$this->setPhpName('Settlement');
		$this->setClassname('Settlement');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		$this->setPrimaryKeyMethodInfo('settlement_id_seq');
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('CONTRACT_ID', 'ContractId', 'INTEGER', 'contract', 'ID', true, null, null);
		$this->addColumn('DATE', 'Date', 'DATE', true, null, null);
		$this->addColumn('INTEREST', 'Interest', 'NUMERIC', true, 15, null);
		$this->addColumn('PAID', 'Paid', 'NUMERIC', true, 15, 0);
		$this->addColumn('CAPITALIZED', 'Capitalized', 'NUMERIC', true, 15, 0);
		$this->addColumn('BALANCE', 'Balance', 'NUMERIC', true, 15, 0);
		$this->addColumn('BALANCE_REDUCTION', 'BalanceReduction', 'NUMERIC', true, 15, 0);
		$this->addColumn('NOTE', 'Note', 'LONGVARCHAR', false, null, null);
		$this->addColumn('BANK_ACCOUNT', 'BankAccount', 'VARCHAR', false, 255, null);
		$this->addColumn('CASH', 'Cash', 'BOOLEAN', true, null, false);
		$this->addColumn('SETTLEMENT_TYPE', 'SettlementType', 'VARCHAR', true, 255, 'in_period');
		$this->addColumn('MANUAL_INTEREST', 'ManualInterest', 'BOOLEAN', true, null, false);
		$this->addColumn('MANUAL_BALANCE', 'ManualBalance', 'BOOLEAN', true, null, false);
		$this->addColumn('DATE_OF_PAYMENT', 'DateOfPayment', 'DATE', false, null, null);
		$this->addColumn('CURRENCY_RATE', 'CurrencyRate', 'NUMERIC', true, 15, 1);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('Contract', 'Contract', RelationMap::MANY_TO_ONE, array('contract_id' => 'id', ), 'CASCADE', 'CASCADE');
	} // buildRelations()

	/**
	 * 
	 * Gets the list of behaviors registered for this table
	 * 
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'symfony' => array('form' => 'true', 'filter' => 'true', ),
			'symfony_behaviors' => array(),
		);
	} // getBehaviors()

} // SettlementTableMap
