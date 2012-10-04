<?php


/**
 * This class defines the structure of the 'contract' table.
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
class ContractTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.ContractTableMap';

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
		$this->setName('contract');
		$this->setPhpName('Contract');
		$this->setClassname('Contract');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		$this->setPrimaryKeyMethodInfo('contract_id_seq');
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('CREDITOR_ID', 'CreditorId', 'INTEGER', 'creditor', 'ID', true, null, null);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'DATE', true, null, null);
		$this->addColumn('ACTIVATED_AT', 'ActivatedAt', 'DATE', false, null, null);
		$this->addColumn('PERIOD', 'Period', 'INTEGER', true, null, null);
		$this->addColumn('INTEREST_RATE', 'InterestRate', 'NUMERIC', true, 5, null);
		$this->addColumn('AMOUNT', 'Amount', 'NUMERIC', true, 15, null);
		$this->addColumn('NAME', 'Name', 'VARCHAR', true, 255, null);
		$this->addColumn('CLOSED_AT', 'ClosedAt', 'DATE', false, null, null);
		$this->addColumn('NOTE', 'Note', 'LONGVARCHAR', false, null, null);
		$this->addForeignKey('CURRENCY_CODE', 'CurrencyCode', 'CHAR', 'currency', 'CODE', true, 3, null);
		$this->addColumn('FIRST_SETTLEMENT_DATE', 'FirstSettlementDate', 'DATE', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('Creditor', 'Creditor', RelationMap::MANY_TO_ONE, array('creditor_id' => 'id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('Currency', 'Currency', RelationMap::MANY_TO_ONE, array('currency_code' => 'code', ), 'RESTRICT', 'CASCADE');
    $this->addRelation('Payment', 'Payment', RelationMap::ONE_TO_MANY, array('id' => 'contract_id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('Settlement', 'Settlement', RelationMap::ONE_TO_MANY, array('id' => 'contract_id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('Regulation', 'Regulation', RelationMap::ONE_TO_MANY, array('id' => 'contract_id', ), 'RESTRICT', 'CASCADE');
    $this->addRelation('Unpaid', 'Unpaid', RelationMap::ONE_TO_MANY, array('id' => 'contract_id', ), 'RESTRICT', 'CASCADE');
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
			'symfony_timestampable' => array('create_column' => 'created_at', ),
		);
	} // getBehaviors()

} // ContractTableMap
