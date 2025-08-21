import { Component } from '@angular/core';
import { LandingHeaderComponent } from '../../landing-header/landing-header.component';
import { RouterLink } from '@angular/router';
import { CarouselComponent } from '../../carousel/carousel.component';

@Component({
  selector: 'app-landing',
  imports: [LandingHeaderComponent, RouterLink, CarouselComponent],
  templateUrl: './landing.component.html',
  styleUrl: './landing.component.scss'
})
export class LandingComponent {

}
