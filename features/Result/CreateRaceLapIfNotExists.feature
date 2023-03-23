Feature: It can create Race Laps

  Scenario: It saves a new race lap
    Given the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" exists
    When a client creates the race lap for the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" at lap "10"
    Then the race lap is saved
    And the id of the race lap for the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" at lap "10" is returned

  Scenario: It tries to create an existing race lap
    Given the race lap "Max Verstappen At Dutch GP 2022 Race Lap 10" exists
    When a client creates the race lap for the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" at lap "10"
    Then the race lap is not duplicated
    And the id of the race lap for the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" at lap "10" is returned

  Scenario: It adds a second race lap
    Given the race lap "Max Verstappen At Dutch GP 2022 Race Lap 10" exists
    When a client creates the race lap for the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" at lap "11"
    And the id of the race lap for the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" at lap "11" is returned
