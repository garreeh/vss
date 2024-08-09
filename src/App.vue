<template>
  <ion-app>
    <ion-split-pane v-if="showSidebar" content-id="main-content">
      <ion-menu content-id="main-content" type="overlay" :disabled="disableMenu">
        <ion-content>
          <ion-list id="inbox-list">
            <!-- <ion-list-header>{{ userName }}</ion-list-header> -->
            <ion-menu-toggle :auto-hide="false" v-for="(p, i) in appPages" :key="i">
              <ion-item
                class="menu-item"
                @click="handleItemClick(p, i, $event)"
                :class="{ selected: selectedIndex === i }"
              >
                <ion-icon aria-hidden="true" slot="start" :ios="p.iosIcon" :md="p.mdIcon"></ion-icon>
                <ion-label>{{ p.title }}</ion-label>
                <ion-icon
                  v-if="p.subPages"
                  slot="end"
                  :icon="isOpen(p.title) ? chevronDownOutline : chevronForwardOutline"
                ></ion-icon>
              </ion-item>
              <ion-list v-if="p.subPages && isOpen(p.title)">
                <ion-item
                  v-for="(subPage, j) in p.subPages"
                  :key="j"
                  @click="handleSubItemClick(i, j, subPage.url, $event)"
                  :class="{ selected: selectedIndex === `${i}-${j}` }"
                  class="sub-item"
                >
                  <ion-icon aria-hidden="true" slot="start" :ios="subPage.iosIcon" :md="subPage.mdIcon"></ion-icon>
                  <ion-label>{{ subPage.title }}</ion-label>
                </ion-item>
              </ion-list>
            </ion-menu-toggle>
          </ion-list>
        </ion-content>
      </ion-menu>
      <ion-router-outlet id="main-content"></ion-router-outlet>
    </ion-split-pane>
    <ion-router-outlet v-else id="main-content"></ion-router-outlet>
  </ion-app>
</template>

<script setup>
import { ref, watchEffect, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import {
  IonApp,
  IonContent,
  IonIcon,
  IonItem,
  IonLabel,
  IonList,
  IonListHeader,
  IonMenu,
  IonMenuToggle,
  IonRouterOutlet,
  IonSplitPane,
} from '@ionic/vue';
import {
  homeOutline,
  homeSharp,
  personOutline,
  personSharp,
  calendarOutline,
  calendarSharp,
  cashOutline,
  cashSharp,
  clipboardOutline,
  clipboardSharp,
  documentOutline,
  documentSharp,
  settingsOutline,
  settingsSharp,
  chevronForwardOutline,
  chevronDownOutline,
} from 'ionicons/icons';

// Sidebar State
const selectedIndex = ref(0);
const showSidebar = ref(true);
const openAccordions = ref([]);
const disableMenu = ref(false);

const appPages = [
  {
    title: 'Dashboard',
    url: '/home',
    iosIcon: homeOutline,
    mdIcon: homeSharp,
  },
  // {
  //   title: 'My Pets',
  //   url: '/pet',
  //   iosIcon: personOutline,
  //   mdIcon: personSharp,
  // },
  {
    title: 'Setup',
    iosIcon: settingsOutline,
    mdIcon: settingsSharp,
    subPages: [
      { title: 'Preferences', url: '/setup/services', iosIcon: settingsOutline, mdIcon: settingsSharp },
      { title: 'Account', url: '/setup/user', iosIcon: personOutline, mdIcon: personSharp },
    ],
  },
];

const route = useRoute();
const router = useRouter();

// Watch route changes to toggle sidebar visibility
watchEffect(() => {
  const shouldShowSidebar = !['/login', '/register', '/forgot-password'].includes(route.path);
  showSidebar.value = shouldShowSidebar;

  // This is for testing only.
  // console.log(`showSidebar: ${showSidebar.value}, Current path: ${route.path}`);
});


const path = window.location.pathname.split('/')[1];
if (path) {
  selectedIndex.value = appPages.findIndex((page) => page.title.toLowerCase() === path.toLowerCase());
}

const userName = ref('Guest');

onMounted(() => {
  const firstname = localStorage.getItem('clientFirstname') || 'Guest';
  const lastname = localStorage.getItem('clientLastname') || '';
  userName.value = `${firstname} ${lastname}`;
});

const toggleAccordion = (title) => {
  if (openAccordions.value.includes(title)) {
    openAccordions.value = openAccordions.value.filter((t) => t !== title);
  } else {
    openAccordions.value.push(title);
  }
};

const isOpen = (title) => openAccordions.value.includes(title);

const handleItemClick = (page, index, event) => {
  if (page.subPages) {
    event.stopPropagation();
    event.preventDefault();
    toggleAccordion(page.title);
  } else {
    selectedIndex.value = index;
    router.push(page.url);
  }
};

const handleSubItemClick = (parentIndex, subIndex, url, event) => {
  selectedIndex.value = `${parentIndex}-${subIndex}`;
  router.push(url);
};

</script>

<style scoped>
.sub-item {
  padding-left: 2rem;
}

ion-menu ion-content {
  --background: var(--ion-item-background, var(--ion-background-color, #fff));
}

ion-menu.md ion-content {
  --padding-start: 8px;
  --padding-end: 8px;
  --padding-top: 20px;
  --padding-bottom: 20px;
}

ion-menu.md ion-list {
  padding: 20px 0;
}

ion-menu.md ion-note {
  margin-bottom: 30px;
}

ion-menu.md ion-list-header,
ion-menu.md ion-note {
  padding-left: 10px;
}

ion-menu.md ion-list#inbox-list {
  border-bottom: 1px solid var(--ion-background-color-step-150, #d7d8da);
}

ion-menu.md ion-list#inbox-list ion-list-header {
  font-size: 22px;
  font-weight: 600;

  min-height: 20px;
}

ion-menu.md ion-list#labels-list ion-list-header {
  font-size: 16px;

  margin-bottom: 18px;

  color: #757575;

  min-height: 26px;
}

ion-menu.md ion-item {
  --padding-start: 10px;
  --padding-end: 10px;
  border-radius: 4px;
}

ion-menu.md ion-item.selected {
  --background: rgba(var(--ion-color-primary-rgb), 0.14);
}

ion-menu.md ion-item.selected ion-icon {
  color: var(--ion-color-primary);
}

ion-menu.md ion-item ion-icon {
  color: #616e7e;
}

ion-menu.md ion-item ion-label {
  font-weight: 500;
}

ion-menu.ios ion-content {
  --padding-bottom: 20px;
}

ion-menu.ios ion-list {
  padding: 20px 0 0 0;
}

ion-menu.ios ion-note {
  line-height: 24px;
  margin-bottom: 20px;
}

ion-menu.ios ion-item {
  --padding-start: 16px;
  --padding-end: 16px;
  --min-height: 50px;
}

ion-menu.ios ion-item.selected ion-icon {
  color: var(--ion-color-primary);
}

ion-menu.ios ion-item ion-icon {
  font-size: 24px;
  color: #73849a;
}

ion-menu.ios ion-list#labels-list ion-list-header {
  margin-bottom: 8px;
}

ion-menu.ios ion-list-header,
ion-menu.ios ion-note {
  padding-left: 16px;
  padding-right: 16px;
}

ion-menu.ios ion-note {
  margin-bottom: 8px;
}

ion-note {
  display: inline-block;
  font-size: 16px;

  color: var(--ion-color-medium-shade);
}

ion-item.selected {
  --color: var(--ion-color-primary);
}

/* Add cursor pointer */
.menu-item {
  cursor: pointer;
}

.sub-item {
  cursor: pointer;
}
</style>
