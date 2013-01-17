<?php


/**
 * This class defines the structure of the 'allocation' table.
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
class AllocationTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.AllocationTableMap';

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
		$this->setName('allocation');
		$this->setPhpName('Allocation');
		$this->setClassname('Allocation');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		$this->setPrimaryKeyMethodInfo('allocation_id_seq');
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('PAID', 'Paid', 'NUMERIC', true, null, 0);
		$this->addColumn('BALANCE_REDUCTION', 'BalanceReduction', 'NUMERIC', true, null, 0);
		$this->addForeignKey('OUTGOING_PAYMENT_ID', 'OutgoingPaymentId', 'INTEGER', 'outgoing_payment', 'ID', true, null, null);
		$this->addForeignKey('SETTLEMENT_ID', 'SettlementId', 'INTEGER', 'settlement', 'ID', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('OutgoingPayment', 'OutgoingPayment', RelationMap::MANY_TO_ONE, array('outgoing_payment_id' => 'id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('Settlement', 'Settlement', RelationMap::MANY_TO_ONE, array('settlement_id' => 'id', ), 'CASCADE', 'CASCADE');
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

} // AllocationTableMap
