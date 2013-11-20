<?php


/**
 * This class defines the structure of the 'payment' table.
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
class PaymentTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.PaymentTableMap';

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
		$this->setName('payment');
		$this->setPhpName('Payment');
		$this->setClassname('Payment');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		$this->setPrimaryKeyMethodInfo('payment_id_seq');
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('CONTRACT_ID', 'ContractId', 'INTEGER', 'contract', 'ID', true, null, null);
		$this->addColumn('DATE', 'Date', 'DATE', true, null, null);
		$this->addColumn('AMOUNT', 'Amount', 'NUMERIC', true, 15, 0);
		$this->addColumn('NOTE', 'Note', 'LONGVARCHAR', false, null, null);
		$this->addColumn('CASH', 'Cash', 'BOOLEAN', true, null, false);
		$this->addColumn('SENDER_BANK_ACCOUNT', 'SenderBankAccount', 'VARCHAR', false, 255, null);
		$this->addColumn('PAYMENT_TYPE', 'PaymentType', 'VARCHAR', true, 255, 'payment');
		$this->addForeignKey('BANK_ACCOUNT_ID', 'BankAccountId', 'INTEGER', 'bank_account', 'ID', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('BankAccount', 'BankAccount', RelationMap::MANY_TO_ONE, array('bank_account_id' => 'id', ), 'RESTRICT', 'CASCADE');
    $this->addRelation('Contract', 'Contract', RelationMap::MANY_TO_ONE, array('contract_id' => 'id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('Settlement', 'Settlement', RelationMap::ONE_TO_MANY, array('id' => 'payment_id', ), 'SET NULL', 'CASCADE');
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

} // PaymentTableMap
