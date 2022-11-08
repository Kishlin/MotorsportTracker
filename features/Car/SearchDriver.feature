Feature: It can search a car

  Scenario: It searches an existing car
    Given the car "Red Bull Racing 2022 First Car" exists
    When a client searches for the car "1" of "Red Bull Racing" for "Formula One" in "2022"
    Then the id of the car "Red Bull Racing 2022 First Car" is returned

  Scenario: It searches a car that does not exist
    Given the "car" "Red Bull Racing 2022 First Car" does not exist yet
    When a client searches for the car "1" of "Red Bull Racing" for "Formula One" in "2022"
    Then it does not receive any car id
