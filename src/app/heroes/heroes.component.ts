import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { Hero } from '../hero';
import { HeroDetailComponent } from '../hero-detail/hero-detail.component';
import { HeroService } from '../hero.service';
import { MessageService } from '../message.service';

@Component({
  selector: 'app-heroes',
  standalone: true,
  imports: [CommonModule, HeroDetailComponent, RouterModule],
  templateUrl: './heroes.component.html',
  styleUrl: './heroes.component.css'
})
export class HeroesComponent implements OnInit {

  heroes: Hero[] = [];

  constructor(private heroService: HeroService, private messageService: MessageService) { }

  ngOnInit(): void {
    this.getHeroes();
  }
  getHeroes(): void {
    this.heroService.getHeroes()
      .subscribe(heroes => this.heroes = heroes);
  }

  add(name: string, alterego: string, superpower: string): void {
    name = name.trim();
    alterego = alterego.trim();
    superpower = superpower.trim();

    if (!name || !alterego || !superpower) {
      return; // Validación básica para asegurarse de que todos los campos estén llenos
    }

    const newHero: Hero = {
      name,
      alterego,
      superpower,
    };

    this.heroService.addHero(newHero).subscribe((hero) => {
      this.heroes.push(hero); // Añade el nuevo héroe a la lista
    });
  }

  delete(hero: Hero): void {
    this.heroes = this.heroes.filter(h => h !== hero);
    this.heroService.deleteHero(hero.id!).subscribe();
  }
}
