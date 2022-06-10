Feature: It can create Drivers

  Scenario: It saves a new driver
    Given a country exists
    When a client creates a new driver for the country
    Then the driver is saved

  Scenario: It cannot duplicate drivers
    Given a country exists
    And a driver exists
    When a client creates a driver with same firstname and name
    Then the driver creation is declined

  Scenario: It cannot create drivers for a missing country
    When a client creates a driver for a missing country
    Then the driver creation is declined
