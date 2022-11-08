Feature: It can search a driver

  Scenario: It searches an existing driver
    Given the driver "Max Verstappen" exists
    When a client searches for the driver "Max Verstappen"
    Then the id of the driver "Max Verstappen" is returned

  Scenario: It searches a driver that does not exist
    Given the "driver" "Max Verstappen" does not exist yet
    When a client searches for the driver "Max Verstappen"
    Then it does not receive any driver id
