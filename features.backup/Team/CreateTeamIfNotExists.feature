Feature: It can create Teams

  Scenario: It saves a new team
    Given the season "Formula One 2022" exists
    When a client creates a team with name "Red Bull Racing" and color "#0000c6" and ref "41be2072-17ab-455f-8522-8b96bc315e47"
    Then the team is saved
    And the id of the team with ref "41be2072-17ab-455f-8522-8b96bc315e47" is returned

  Scenario: It returns the id of an existing team
    Given the "Red Bull Racing" team exists
    When a client creates a team with the same name color and ref
    Then the team is not duplicated
    And the id of the team with ref "41be2072-17ab-455f-8522-8b96bc315e47" is returned