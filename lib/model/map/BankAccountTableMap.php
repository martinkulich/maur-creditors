<?php


/**
 * This class defines the structure of the 'bank_account' table.
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
class BankAccountTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.BankAccountTableMap';

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
		$this->setName('bank_account');
		$this->setPhpName('BankAccount');
		$this->setClassname('BankAccount');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		$this->setPrimaryKeyMethodInfo('bank_account_id_seq');
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('NAME', 'Name', 'VARCHAR', true, 255, null);
		$this->addColumn('NUMBER', 'Number', 'VARCHAR', true, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('OutgoingPayment', 'OutgoingPayment', RelationMap::ONE_TO_MANY, array('id' => 'bank_account_id', ), 'SET NULL', 'CASCADE');
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

} // BankAccountTableMap
