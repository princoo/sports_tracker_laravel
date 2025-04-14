<?php

namespace App\Services\Player;

use App\Models\Player;

class PlayerService
{
    // convert this nest services into laravel service container
    //     @Injectable()
    // export class PlayersService {
    //   constructor(private prisma: PrismaService) {}
    //   create(createPlayerDto: CreatePlayerDto, siteId: string) {
    //     return this.prisma.player.create({
    //       data: { ...createPlayerDto, siteId },
    //     });
    //   }

    //   findAll() {
    //     return this.prisma.player.findMany();
    //   }

    //   findAllBySite(siteId: string) {
    //     return this.prisma.player.findMany({
    //       where: { siteId },
    //     });
    //   }

    //   findOne(playerId: string) {
    //     return this.prisma.player.findUnique({ where: { id: playerId } });
    //   }

    //   findPlayerCoach(playerId: string) {
    //     return this.prisma.player.findUnique({
    //       where: { id: playerId },
    //       include: { site: { include: { coaches: { include: { user: true } } } } },
    //     });
    //   }

    //   update(id: string, updatePlayerDto: UpdatePlayerDto) {
    //     return this.prisma.player.update({ where: { id }, data: updatePlayerDto });
    //   }

    //   remove(id: string) {
    //     return this.prisma.player.delete({ where: { id } });
    //   }
    // }

    public function create(array $data, string $site_id): Player
    {
        return Player::create(array_merge($data, ['site_id' => $site_id]));
    }

    public function findAll(): array
    {
        return Player::all()->toArray();
    }

    public function findAllBySite(string $siteId): array
    {
        return Player::where('site_id', $siteId)->get()->toArray();
    }

    public function findOne(string $player_id): ?Player
    {
        return Player::find($player_id);
    }

    public function findPlayerCoach(string $playerId): ?Player
    {
        return Player::with('site.coaches.user')->find($playerId);
    }

    public function update(string $id, array $data): ?Player
    {
        $player = Player::find($id);
        if (!$player) {
            return null;
        }
        $player->update($data);
        return $player;
    }

    public function remove(string $id): ?bool
    {
        $player = Player::find($id);
        if (!$player) {
            return null;
        }
        return $player->delete();
    }
}
