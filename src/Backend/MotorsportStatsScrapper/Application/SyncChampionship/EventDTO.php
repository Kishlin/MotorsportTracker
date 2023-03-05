<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\SyncChampionship;

final class EventDTO
{
    /**
     * @param SessionDTO[] $sessions
     */
    private function __construct(
        private readonly string $slug,
        private readonly string $name,
        private readonly ?string $shortName,
        private readonly ?int $startTimeUtc,
        private readonly ?int $endTimeUtc,
        private readonly string $venueName,
        private readonly string $venueSlug,
        private readonly string $countryName,
        private readonly string $countrySlug,
        private readonly string $countryPicture,
        private readonly array $sessions,
    ) {
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function shortName(): ?string
    {
        return $this->shortName;
    }

    public function startTimeUtc(): ?int
    {
        return $this->startTimeUtc;
    }

    public function endTimeUtc(): ?int
    {
        return $this->endTimeUtc;
    }

    public function venueName(): string
    {
        return $this->venueName;
    }

    public function venueSlug(): string
    {
        return $this->venueSlug;
    }

    public function countryName(): string
    {
        return $this->countryName;
    }

    public function countrySlug(): string
    {
        return $this->countrySlug;
    }

    public function countryPicture(): string
    {
        return $this->countryPicture;
    }

    /**
     * @return SessionDTO[]
     */
    public function sessions(): array
    {
        return $this->sessions;
    }

    /**
     * @param array{
     *     slug: string,
     *     name: string,
     *     shortName: null|string,
     *     startTimeUtc: null|int,
     *     endTimeUtc: null|int,
     *     venue: array{
     *         name: string,
     *         slug: string,
     *     },
     *     country: array{
     *         name: string,
     *         slug: string,
     *         picture: string,
     *     },
     *     sessions: array{
     *         session: array{
     *             name: string,
     *             shortName: null|string,
     *             slug: string,
     *             code: null|string,
     *         },
     *         hasResults: bool,
     *         startTimeUtc: null|int,
     *         endTimeUtc: null|int,
     *     }[],
     * } $data
     */
    public static function fromData(array $data): self
    {
        $sessions = array_map(
            static function ($data) { return SessionDTO::fromData($data); },
            $data['sessions'],
        );

        return new self(
            $data['slug'],
            $data['name'],
            $data['shortName'],
            $data['startTimeUtc'],
            $data['endTimeUtc'],
            $data['venue']['name'],
            $data['venue']['slug'],
            $data['country']['name'],
            $data['country']['name'],
            $data['country']['picture'],
            $sessions,
        );
    }
}
