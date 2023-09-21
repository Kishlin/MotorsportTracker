Feature: It can create Classifications

  Scenario: It saves a new classification
    Given the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" exists
    When a client creates the classification for entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race"
    Then the classification is saved
    And the id of the classification of car number "33" in session "Dutch Grand Prix 2022 Race"

  Scenario: It tries to create an existing classification
    Given the classification "Max Verstappen At Dutch GP 2022 Race" exists
    When a client creates the classification for the same entry
    Then the classification is not duplicated
    And the id of the classification of car number "33" in session "Dutch Grand Prix 2022 Race"
