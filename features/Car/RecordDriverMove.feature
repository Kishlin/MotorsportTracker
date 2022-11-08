Feature: It can record Driver Moves

  @backoffice
  Scenario: It saves a new driver move
    Given the car "Red Bull Racing 2022 First Car" exists
    And the driver "Max Verstappen" exists
    When a client records a driver move for driver "Max Verstappen" to the car "Red Bull Racing 2022 First Car" on date "2022-01-01"
    Then the driver move is recorded
    And the racer data for "Max Verstappen" is created

  @backoffice
  Scenario: It handles a mid-season driver move
    Given the racer for "Gasly At Red Bull Racing In 2019" exists
    And the car "Toro Rosso 2019 First Car" exists
    When a client records a driver move for driver "Pierre Gasly" to the car "Toro Rosso 2019 First Car" on date "2019-08-12"
    Then the racer data for "Pierre Gasly" in car "Red Bull Racing 2019 Second Car" is from "2019-01-01 00:00:00" to "2019-08-11 23:59:59"
    And the racer data for "Pierre Gasly" in car "Toro Rosso 2019 First Car" is from "2019-08-12 00:00:00" to "2019-12-31 23:59:59"

  Scenario: It cannot duplicate moves for a driver
    Given the driver move "Verstappen To Red Bull Racing In 2022" was already recorded
    When a client records a driver move with the same driver and date
    Then the driver move recording is declined

  Scenario: It cannot duplicate moves for a car
    Given the driver move "Verstappen To Red Bull Racing In 2022" was already recorded
    When a client records a driver move with the same car and date
    Then the driver move recording is declined

  Scenario: It cannot record driver moves for a missing car
    Given the driver "Max Verstappen" exists
    And the car "Red Bull Racing 2022 First Car" does not exist yet
    When a client records a driver move for a missing car
    Then the driver move recording is declined

  Scenario: It cannot record driver moves for a missing driver
    Given the car "Red Bull Racing 2022 First Car" exists
    And the driver "Max Verstappen" does not exist yet
    When a client records a driver move for a missing driver
    Then the driver move recording is declined
