<?php


/**
 * This class defines the structure of the 'unpaid' table.
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
class UnpaidTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.UnpaidTableMap';

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
		$this->setName('unpaid');
		$this->setPhpName('Unpaid');
		$this->setClassname('Unpaid');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'VARCHAR', true, 255, null);
		$this->addColumn('CREDITOR_FULLNAME', 'CreditorFirstname', 'VARCHAR', false, 255, null);
		$this->addForeignKey('CREDITOR_ID', 'CreditorId', 'INTEGER', 'creditor', 'ID', false, null, null);
		$this->addForeignKey('CONTRACT_ID', 'ContractId', 'INTEGER', 'contract', 'ID', false, null, null);
		$this->addColumn('CONTRACT_NAME', 'ContractName', 'VARCHAR', false, 255, null);
		$this->addColumn('SETTLEMENT_DATE', 'SettlementDate', 'DATE', false, null, null);
		$this->addColumn('CREDITOR_UNPAID', 'CreditorUnpaid', 'NUMERIC', false, 15, null);
		$this->addColumn('CONTRACT_UNPAID', 'ContractUnpaid', 'NUMERIC', false, 15, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('Contract', 'Contract', RelationMap::MANY_TO_ONE, array('contract_id' => 'id', ), 'RESTRICT', 'CASCADE');
    $this->addRelation('Creditor', 'Creditor', RelationMap::MANY_TO_ONE, array('creditor_id' => 'id', ), 'RESTRICT', 'CASCADE');
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

} // UnpaidTableMap
