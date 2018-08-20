import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from "@angular/common/http";
import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';
import { WpPageComponent } from './wp-page/wp-page.component';
import { PageContentComponent } from './wp-page/page-content/page-content.component';
import { WpPagesService } from './wp-pages.service';
import { WpPostsService } from './wp-posts.service';
import { FilterByCategory } from './filterByCategory';

@NgModule({
  declarations: [
    AppComponent,
    WpPageComponent,
    PageContentComponent,
    FilterByCategory
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    AppRoutingModule
  ],
  providers: [WpPagesService, WpPostsService],
  bootstrap: [AppComponent]
})
export class AppModule { }
