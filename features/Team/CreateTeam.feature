Feature: It can create Teams

  Scenario: It saves a new team
    Given a country exists
    When a client creates a new team for the country
    Then the team is saved

  Scenario: It cannot duplicate team
    Given a country exists
    And a team exists for the country
    When a client creates a team with same name
    Then the team creation is declined

  Scenario: It cannot create teams for a missing country
    When a client creates a team for a missing country
    Then the team creation is declined
