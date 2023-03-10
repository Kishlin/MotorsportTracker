Feature: It can create Drivers

  Scenario: It saves a new driver
    Given the country "Netherlands" exists
    When a client creates the driver "Max Verstappen" for the country "Netherlands"
    Then the driver is saved
    And the id of "Max Verstappen" is returned

  Scenario: It tries to create an existing driver
    Given the driver "Max Verstappen" exists
    When a client creates a driver with same name
    Then the driver is not duplicated
    And the id "Max Verstappen" is returned
