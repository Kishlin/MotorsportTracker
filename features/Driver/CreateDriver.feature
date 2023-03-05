Feature: It can create Drivers

  Scenario: It saves a new driver
    Given the country "Netherlands" exists
    When a client creates the driver "Max Verstappen" for the country "Netherlands"
    Then the driver is saved

  Scenario: It cannot duplicate drivers
    Given the driver "Max Verstappen" exists
    When a client creates a driver with same name
    Then the driver creation is declined
