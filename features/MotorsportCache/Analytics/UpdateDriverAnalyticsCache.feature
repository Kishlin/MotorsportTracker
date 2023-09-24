Feature: It updates the cache for driver analytics

  @current
  Scenario: It updates the cache for one analytics
    Given the analytics data for Driver "Max Verstappen In Formula One 2023" exist
    And the analytics data for Driver "Lewis Hamilton In Formula One 2023" exist
    When the driver analytics cache is updated for season "Formula One" "2023"
    Then the cache has driver analytics for "Formula One" 2023
    And the cache for driver analytics for "Formula One" 2023, at index 0, is for "Max Verstappen"
    And the cache for driver analytics for "Formula One" 2023, at index 1, is for "Lewis Hamilton"
